<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost'; // or an IP address
$port = 3307;
$dbname = 'test';
$username = '';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request to insert a new comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['commentText'])) {
    $commentText = $conn->real_escape_string($_POST['commentText']);
    $sql = "INSERT INTO comments (comment_text) VALUES ('$commentText')";
    if ($conn->query($sql)) {
        // Redirect to the same page to prevent form resubmission on reload
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        die('Error: ' . $conn->error);
    }
}

// Fetch all comments from the database
$sql = "SELECT comment_id, comment_text FROM comments ORDER BY comment_id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comments Page</title>
</head>
<body>
<h1>Submit a Comment</h1>
<form action="comments.php" method="post">
    <label for="commentText">Comment:</label>
    <input type="text" id="commentText" name="commentText" required>
    <button type="submit">Submit</button>
</form>

<?php if ($result->num_rows > 0): ?>
<h2>Comments:</h2>
<table border="1">
    <tr>
        <th>Comment ID</th>
        <th>Comment Text</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row["comment_id"]; ?></td>
            <td><?php echo $row["comment_text"]; ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>No comments found.</p>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
