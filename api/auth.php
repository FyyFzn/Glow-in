<?php
session_start();
require_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'] ?: $user['username'];
            $_SESSION['profile_pic'] = $user['profile_pic'] ?: 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=200&q=80';
            header("Location: ../home.php");
            exit();
        } else {
            echo "<script>alert('Username atau Password salah!'); window.location.href='../login.php';</script>";
            exit();
        }
    } elseif ($action === 'register') {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Check if passwords match
        if ($password !== $confirm_password) {
            echo "<script>alert('Password tidak sama!'); window.location.href='../register.php';</script>";
            exit();
        }

        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Username sudah terpakai!'); window.location.href='../register.php';</script>";
            exit();
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                header("Location: ../register.php?success=1");
                exit();
            } else {
                $error = $stmt->errorInfo();
                echo "<script>alert('Gagal mendaftar: " . addslashes($error[2]) . "'); window.location.href='../register.php';</script>";
                exit();
            }
        } catch (PDOException $e) {
            echo "<script>alert('Database Error: " . addslashes($e->getMessage()) . "'); window.location.href='../register.php';</script>";
            exit();
        }
    }
} elseif ($method === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'logout') {
        session_destroy();
        header("Location: ../login.php");
        exit();
    }
}
?>
