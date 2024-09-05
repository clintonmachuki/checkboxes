<?php
$host = 'localhost';
$db = 'checkbox_db';
$user = 'root'; // Default XAMPP user
$pass = ''; // Default XAMPP password

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
