<?php
session_start();
include 'db.php';

function generateRandomString($length = 15) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo 'Username already exists';
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generate a unique user ID
    $user_id = generateRandomString();

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (id, username, password) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $username, $hashed_password]);

    // Redirect to the login page
    header('Location: login.html');
    exit();
}
?>
