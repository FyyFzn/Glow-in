<?php
$host = 'localhost';
$dbname = 'glowin_db';
$user = 'root'; // Change if necessary
$pass = ''; // Change if necessary

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
