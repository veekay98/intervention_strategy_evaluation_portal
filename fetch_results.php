<?php
// Database credentials
$host = 'localhost';
$port = 3307; // Adjust if using a different port
$dbname = 'test';
$username = ''; // Fill in your database username
$password = ''; // Fill in your database password

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get coder ID from GET parameters
$coder_id = isset($_GET['coder_id']) ? $_GET['coder_id'] : 0;

$table_name = $coder_id . '_rankings';

// SQL to fetch data, filtering by coder_id
$sql = "SELECT comment_id, strategy_type, question_1, question_2, question_3 FROM $table_name";
$stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $table_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<table style="width:100%; border-collapse: collapse;" border="1">';
    echo '<tr><th>Comment ID</th><th>Strategy Type</th><th>Question 1</th><th>Question 2</th><th>Question 3</th></tr>';

    while($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row["comment_id"] . '</td><td>' . $row["strategy_type"] . '</td><td>' . $row["question_1"] . '</td><td>' . $row["question_2"] . '</td><td>' . $row["question_3"] . '</td></tr>';
    }
    echo '</table>';
} else {
    echo "0 results for coder ID: " . htmlspecialchars($coder_id);
}

$conn->close();
?>
