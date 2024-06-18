<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = 'localhost';
$port = 3307;
$dbname = 'test';
$username = ''; // Fill in your database username
$password = ''; // Fill in your database password

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$coder_id = isset($_GET['coder_id']) ? $_GET['coder_id'] : 'coder1'; // Default coder_id
// Determine the current comment ID
$current_comment_id = isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 1;

// Query to fetch the current comment
$sql = "SELECT comment_text FROM comments WHERE comment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_comment_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $comment_text = $row['comment_text'];
} else {
    $comment_text = "No comments found.";
    $current_comment_id = 0; // Reset if no more comments
}

// Prepare to fetch the next comment ID
$next_comment_id = $current_comment_id + 1;
$sql_next = "SELECT comment_id FROM comments WHERE comment_id = ?";
$stmt_next = $conn->prepare($sql_next);
$stmt_next->bind_param("i", $next_comment_id);
$stmt_next->execute();
$result_next = $stmt_next->get_result();
if ($result_next->num_rows == 0) {
    $next_comment_id = 0; // No more comments after this
}

// Close the database connection
$stmt->close();
$stmt_next->close();
$conn->close();
?>
<?php
// Set error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['comment_id'], $_GET['comment_text'], $_GET['coder_id'])) {
    $comment_id = $_GET['comment_id'];
    $comment_text = $_GET['comment_text'];
    $coder_id = $_GET['coder_id'];
  }



// Database credentials
$host = 'localhost';
$port = 3307;
$dbname = 'test';
$username = ''; // Fill in your database username
$password = ''; // Fill in your database password

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the last comment
$sql = "SELECT comment_text FROM comments WHERE comment_id = {$current_comment_id}";
$result = $conn->query($sql);

// Fetch the result
$lastComment = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastComment = $row['comment_text'];
} else {
    $lastComment = "No comments found.";
}

$comment_text = $lastComment;

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Comment 1</title>
<link rel="stylesheet" href="page2-styles.css">
<style>
    body {a
        text-align: center; /* Center-aligns text elements */
        font-family: Arial, sans-serif; /* Ensures a consistent font is used */
    }
    .modal {
        display: none; /* Initially hides the modal */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    form, #comments, button {
        margin-top: 20px; /* Adds space between elements */
        width: 90%; /* Adjusts the width of the elements */
        margin-left: auto;
        margin-right: auto;
    }
    input[type="text"], input[type="submit"], button {
        padding: 10px; /* Adds padding for better touch interaction */
        margin-top: 10px; /* Additional spacing */
    }
    input[type="radio"] {
        margin-right: 5px;
    }
</style>
<script>
window.onload = function() {
    var form = document.getElementById('strategyForm');
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type === "text" || form.elements[i].type === "radio") {
            form.elements[i].value = ''; // Clear text and radio inputs
        }
        if (form.elements[i].type === "checkbox") {
            form.elements[i].checked = false; // Uncheck checkboxes
        }
    }
};
</script>
<script>
function showDialog(strategyId) {
    var modalId = 'myModal' + strategyId;
    document.getElementById(modalId).style.display = 'block';
    if (document.getElementById('myModal' + (strategyId - 1))) {
        document.getElementById('myModal' + (strategyId - 1)).style.display = 'none';
    }
}

function closeModal(strategyId) {
var modalId = 'myModal' + strategyId;
document.getElementById(modalId).style.display = 'none';
}

window.onclick = function(event) {
var modals = document.querySelectorAll('.modal');
modals.forEach(modal => {
if (event.target == modal) {
modal.style.display = "none";
}
});
}

function handleSubmit(strategyId) {
if (strategyId < totalStrategies) {
showDialog(strategyId + 1);
} else {
alert('Thank you for your responses!');
// Here you can collect all form data and send to server
}
return false;
}
</script>
</head>
<body>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$port = 3307;
$dbname = 'test';
$username = '';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT strategy_type, response FROM test.coders_strategy_choices WHERE comment_id = {$current_comment_id}";
$result = $conn->query($sql);
$strategies = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $strategies[] = $row;
    }
}
$conn->close();
?>
<form id="newCommentForm" onsubmit="addComment(); return false;">
    <input type="text" id="newComment" placeholder="Add a comment..." />
    <button type="button" onclick="addComment()">Post Comment</button>
</form>
<div id="comments">
    <p>Random Comment 1</p>
    <p>Random Comment 2</p>
    <p>Random Comment 3</p>
    <!-- Display the last comment from the database -->
    <p><?php echo htmlspecialchars($lastComment); ?></p>
</div>
<?php if ($current_comment_id > 0): ?>
  <!-- <button id="evaluateNextComment" onclick="attemptNextComment()" disabled>
    Evaluate Next Comment
</button> -->
<button id="evaluateNextComment" onclick="window.location.href='?comment_id=<?php echo $next_comment_id; ?>&coder_id=<?php echo $coder_id; ?>'" disabled>
    Evaluate Next Comment
</button>
<!-- <div id="warningModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeWarningModal()">&times;</span>
        <p>Ranking for this comment must be completed before the next comment can be looked at.</p>
    </div>
</div> -->
<?php else: ?>
  <script>
      alert("You have rated all strategies for all comments. You will now be redirected to the home page in 5 seconds.");
      setTimeout(function() {
          window.location.href = 'index.html?evaluation_complete=true';
      }, 5000);
  </script>
<p>No more comments to evaluate.</p>
<?php endif; ?>

<script>



function validateAndSubmit(strategyType, isLast) {
    let messages = []; // Array to store warning messages
    console.log(`Validating responses for strategyType ${strategyType}`);

    // Assume there are 3 questions as per your PHP loop
    for (let q = 1; q <= 3; q++) {
        let radios = document.getElementsByName(`response_${q}_${strategyType}`);
        let isChecked = Array.from(radios).some(radio => radio.checked);

        if (!isChecked) {
            messages.push(`Please select an option for Criterion ${q} for Strategy ${strategyType}.`); // Add warning message for this question
            console.log(`Missing selection for question ${q} for strategy ${strategyType}`);
        }
    }

    // Check if there were any warnings collected
    if (messages.length > 0) {
        console.error("Validation errors: ", messages.join("\n"));
        alert(messages.join("\n")); // Join all messages with newline characters and show alert
        return false; // Stop the function if there are missing selections
    }

    // If all questions have been answered, proceed to submit
    submitStrategy(strategyType, isLast);
}

function submitStrategy(strategyType, isLast) {
    var form = document.getElementById('form' + strategyType);
    var formData = new FormData(form);
    formData.append('strategy_type', strategyType);

    console.log(`Submitting data for strategyType ${strategyType}`);
    fetch('save_strategy.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log(`Response received from server for strategyType ${strategyType}`);
        return response.text();
    })
    .then(data => {
        console.log(`Server response: ${data}`);
        alert(data); // This alert shows the server response, typically "Responses saved successfully".
        if (isLast) {
            alert('Thank you for your responses! Loading next comment...');
            // Redirect to the next comment
            window.location.href = '?comment_id=' + (parseInt(form.elements['comment_id'].value) + 1) + '&coder_id=' + form.elements['coder_id'].value;
        } else {
            showDialog(strategyType + 1);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

    closeModal(strategyType);
    return false;
}

function addComment() {
    var comment = document.getElementById("newComment").value;
    console.log(`Adding new comment: ${comment}`);
    var p = document.createElement("p");
    p.textContent = comment;
    document.getElementById("comments").appendChild(p);
    document.getElementById("newComment").value = '';
}


</script>

<button id="myBtn" onclick="showDialog(1); console.log('Opening dialog for the first strategy');">Choose the best strategy</button>
<?php foreach ($strategies as $index => $strategy): ?>
  <?php error_log("Loading strategy modal for strategy type: " . $strategy['strategy_type']); ?>
  <div id="myModal<?php echo $strategy['strategy_type']; ?>" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal(<?php echo $strategy['strategy_type']; ?>); console.log('Closing modal for strategy type: <?php echo $strategy['strategy_type']; ?>');">&times;</span>
      <form id="form<?php echo $strategy['strategy_type']; ?>" autocomplete="off">
        <input type="hidden" name="comment_id" value="<?php echo $current_comment_id; ?>" autocomplete="off">
        <input type="hidden" name="coder_id" value="<?php echo $coder_id; ?>" autocomplete="off">
        <h3>Intervention Comment: <?php echo htmlspecialchars($strategy['response']); ?></h3>
        <p>Rate this strategy on a scale of 1 to 5 according to each criterion</p>
        <?php
        $questionTexts = [
            1 => "Criterion 1: Perceived by other social media users as favorable",
            2 => "Criterion 2: Alters the discussion in more positive, objective, and less antagonistic ways",
            3 => "Criterion 3: Changes the online behavior of the bully or agitator"
        ];
        foreach ($questionTexts as $q => $text):
            error_log("Setting up question $q for strategy " . $strategy['strategy_type']);
        ?>
        <p><?php echo $text; ?></p>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <label>
              <input type="radio" name="response_<?php echo $q; ?>_<?php echo $strategy['strategy_type']; ?>" value="<?php echo $i; ?>" autocomplete="off"> <?php echo $i; ?>
          </label>
        <?php endfor; ?>
        <br>
        <?php endforeach; ?>
        <button type="button" onclick="validateAndSubmit(<?php echo $strategy['strategy_type']; ?>, <?php echo $index == count($strategies) - 1; ?>); console.log('Submitting form for strategy <?php echo $strategy['strategy_type']; ?>');">
            <?php echo ($index == count($strategies) - 1) ? 'Submit All' : 'Next'; ?>
        </button>
      </form>
    </div>
  </div>
<?php endforeach; ?>
<script>
var totalStrategies = <?php echo count($strategies); ?>;
</script>
<script>


// Function to check if all questions have been answered
function checkAllResponses() {
    var allAnswered = true; // Flag to determine if all questions are answered

    // Check each strategy
    <?php foreach ($strategies as $strategy): ?>
        for (let q = 1; q <= 3; q++) { // Assuming there are 3 questions per strategy
            let radios = document.getElementsByName(`response_${q}`);
            if (!Array.from(radios).some(radio => radio.checked)) {
                console.log(`Unanswered question found: Strategy ${strategy['strategy_type']}, Question ${q}`);
                allAnswered = false; // Found an unanswered question
                break;
            }
        }
        if (!allAnswered) {
            console.log(`Stopping checks as an unanswered question was found for strategy ${strategy['strategy_type']}`);
            break; // Stop checking if an unanswered question is found
        }
    <?php endforeach; ?>

    console.log(`All questions answered: ${allAnswered}`);
    // Enable or disable the button based on whether all questions are answered
    document.getElementById('evaluateNextComment').disabled = !allAnswered;
}

// Attach the checkAllResponses function to change events for all radio buttons
document.addEventListener('DOMContentLoaded', function() {
    var radios = document.querySelectorAll('input[type="radio"]');
    console.log("Attaching event listeners to radio buttons");
    radios.forEach(radio => radio.addEventListener('change', checkAllResponses));
});

function validateAndSubmit(strategyType, isLast) {
    let messages = []; // Array to store warning messages
    console.log(`Validating responses for strategyType ${strategyType}`);

    // Assume there are 3 questions as per your PHP loop
    for (let q = 1; q <= 3; q++) {
        let radios = document.getElementsByName(`response_${q}`);
        let isChecked = Array.from(radios).some(radio => radio.checked);

        if (!isChecked) {
            messages.push(`Please select an option for Criterion ${q}.`); // Add warning message for this question
            console.log(`Missing selection for question ${q}`);
        }
    }

    // Check if there were any warnings collected
    if (messages.length > 0) {
        console.error("Validation errors: ", messages.join("\n"));
        alert(messages.join("\n")); // Join all messages with newline characters and show alert
        return false; // Stop the function if there are missing selections
    }

    // If all questions have been answered, proceed to submit
    submitStrategy(strategyType, isLast);
}

function submitStrategy(strategyType, isLast) {
    var form = document.getElementById('form' + strategyType);
    var formData = new FormData(form);
    formData.append('strategy_type', strategyType);

    console.log(`Submitting data for strategyType ${strategyType}`);
    fetch('save_strategy.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log(`Response received from server for strategyType ${strategyType}`);
        return response.text();
    })
    .then(data => {
        console.log(`Server response: ${data}`);
        alert(data); // This alert shows the server response, typically "Responses saved successfully".
        if (isLast) {
            alert('Thank you for your responses! Loading next comment...');
            // Redirect to the next comment
            window.location.href = '?comment_id=' + (parseInt(form.elements['comment_id'].value) + 1) + '&coder_id=' + form.elements['coder_id'].value;
        } else {
            showDialog(strategyType + 1);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

    closeModal(strategyType);
    return false;
}

function addComment() {
    var comment = document.getElementById("newComment").value;
    console.log(`Adding new comment: ${comment}`);
    var p = document.createElement("p");
    p.textContent = comment;
    document.getElementById("comments").appendChild(p);
    document.getElementById("newComment").value = '';
}


</script>
</body>
</html>
