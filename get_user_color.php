<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];

// Fetch the user's selected color
$stmt = $pdo->prepare("SELECT box_color FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

echo $user['box_color'];
?>
