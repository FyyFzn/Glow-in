<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'] ?? null;
        $nameback = $_POST['nameback'] ?? null;
        $bio = $_POST['bio'] ?? null;
        $location = $_POST['location'] ?? null;
        $profile_pic = $_POST['profile_pic'] ?? null;
        $header_pic = $_POST['header_pic'] ?? null;
        
        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, nameback = ?, bio = ?, location = ?, profile_pic = ?, header_pic = ? WHERE id = ?");
            $stmt->execute([$name,$nameback, $bio, $location, $profile_pic, $header_pic, $user_id]);
            
            // Update session so sidebar reflects changes instantly
            $_SESSION['name'] = $name ?: $_SESSION['username'];
            if ($profile_pic) {
                $_SESSION['profile_pic'] = $profile_pic;
            }
            
            // Redirect back to profile
            header("Location: ../profile.php?success=1");
            exit();
        } catch (PDOException $e) {
            die("Error updating profile: " . $e->getMessage());
        }
    }
}
?>
