<?php
session_start();
include 'db.php';

function generateRandomString($length = 15) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Fetch user information for chat
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat and Checkboxes</title>
    <link rel="stylesheet" href="styles.css">
    <script src="index.js" defer></script>
    <style>
        /* Inline CSS for simplicity; move to styles.css as needed */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .chat-box {
            width: 80%;
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            margin-bottom: 10px;
        }

        .checkboxes-container {
            width: 80%;
            max-height: 300px;
            overflow-y: auto;
        }

        .message {
            margin-bottom: 5px;
        }

        .message span {
            font-weight: bold;
        }

        form {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    
    <!-- Chat Box -->
    <div id="chat" class="chat-box"></div>
    <form id="chatForm">
        <input type="text" id="chatMessage" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>
    
    <!-- Checkboxes -->
    <div id="checkboxes" class="checkboxes-container"></div>
    
    <!-- Pagination Controls -->
    <button id="prevPage">Previous</button>
    <button id="nextPage">Next</button>

    <script>
        // Pass PHP variables to JavaScript
        const username = "<?php echo $username; ?>";
    </script>
</body>
</html>
