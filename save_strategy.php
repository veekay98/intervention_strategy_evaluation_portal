<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = 'localhost';
$port = 3307;
$dbname = 'test';
$username = ''; // Your database username
$password = ''; // Your database password

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$strategy_type = $_POST['strategy_type'] ?? null;
$comment_id = $_POST['comment_id'] ?? null;
$coder_id = $_POST['coder_id'] ?? null;
$responses = [];

// Collect all responses for each question under the current strategy type
for ($q = 1; $q <= 3; $q++) {
    $inputName = "response_{$q}_{$strategy_type}"; // Constructs the name like response_1_2 for question 1, strategy 2
    if (isset($_POST[$inputName])) {
        $responses[$q] = $_POST[$inputName];
    }
}

$table_name = $coder_id . '_rankings';

// Check if table exists and create if not
$checkTable = $conn->query("SHOW TABLES LIKE '{$table_name}'");
if ($checkTable->num_rows == 0) {
    // SQL to create table if it does not exist
    $createTableSQL = "CREATE TABLE {$table_name} (
        id INT AUTO_INCREMENT PRIMARY KEY,
        comment_id INT,
        strategy_type INT,
        question_1 INT,
        question_2 INT,
        question_3 INT
    )";
    if (!$conn->query($createTableSQL)) {
        echo "Error creating table: " . $conn->error;
        exit; // Stop further execution if table creation fails
    }
}

// Check if the combination already exists
$checkExistence = $conn->prepare("SELECT * FROM {$table_name} WHERE comment_id = ? AND strategy_type = ?");
$checkExistence->bind_param("ii", $comment_id, $strategy_type);
$checkExistence->execute();
$result = $checkExistence->get_result();
if ($result->num_rows > 0) {
    echo "The evaluation of this strategy for this comment has already been completed. Moving on to the next one.";
    // Potentially redirect or take other actions here
} else {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO {$table_name} (comment_id, strategy_type, question_1, question_2, question_3) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiii", $comment_id, $strategy_type, $responses[1], $responses[2], $responses[3]);

    // Execute the query
    if ($stmt->execute()) {
        echo "Responses saved successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$checkExistence->close();
$conn->close();
?>
