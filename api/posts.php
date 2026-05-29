<?php
session_start();
require_once '../config.php';

// Otorisasi: Harus login
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_REQUEST['action'] ?? '';

if ($method === 'POST') {
    if ($action === 'create') {
        $content = $_POST['content'] ?? '';
        $user_id = $_SESSION['user_id'];
        
        if (!empty($content)) {
            $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
            $stmt->execute([$user_id, $content]);
        }
        header("Location: ../home.php");
        exit();
        
    } elseif ($action === 'edit') {
        $post_id = $_POST['post_id'] ?? 0;
        $content = $_POST['content'] ?? '';
        $user_id = $_SESSION['user_id'];
        
        if (!empty($content) && $post_id) {
            // Verify ownership
            $stmt = $pdo->prepare("UPDATE posts SET content = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$content, $post_id, $user_id]);
        }
        header("Location: ../home.php");
        exit();
        
    } elseif ($action === 'delete') {
        $post_id = $_POST['post_id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        
        if ($post_id) {
            // Verify ownership
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
            $stmt->execute([$post_id, $user_id]);
        }
        header("Location: ../home.php");
        exit();
    }
}
?>
