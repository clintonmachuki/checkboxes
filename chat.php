<?php
session_start();
include 'db.php';

function generateRandomString($length = 15) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        exit('Unauthorized');
    }
    $username = $_POST['username'];
    $message = $_POST['message'];

    // Generate a unique chat message ID
    $message_id = generateRandomString();

    // Insert the new chat message into the database
    $stmt = $pdo->prepare("INSERT INTO chat_messages (id, username, message) VALUES (?, ?, ?)");
    $stmt->execute([$message_id, $username, $message]);
} else {
    // Retrieve the last 20 chat messages
    $stmt = $pdo->query("SELECT * FROM chat_messages ORDER BY created_at DESC LIMIT 20");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Reverse the order to show the oldest messages at the top
    $messages = array_reverse($messages);
    echo json_encode($messages);
}
?>
