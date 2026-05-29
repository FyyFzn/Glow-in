<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_REQUEST['action'] ?? '';

if ($method === 'POST') {
    if ($action === 'create') {
        $post_id = $_POST['post_id'] ?? 0;
        $comment_text = $_POST['comment_text'] ?? '';
        $user_id = $_SESSION['user_id'];
        
        if (!empty($comment_text) && $post_id) {
            $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)");
            $stmt->execute([$post_id, $user_id, $comment_text]);
        }
        header("Location: ../detail.php?id=" . $post_id);
        exit();
        
    } elseif ($action === 'edit') {
        $comment_id = $_POST['comment_id'] ?? 0;
        $comment_text = $_POST['comment_text'] ?? '';
        $user_id = $_SESSION['user_id'];
        
        if (!empty($comment_text) && $comment_id) {
            $stmt = $pdo->prepare("UPDATE comments SET comment_text = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$comment_text, $comment_id, $user_id]);
        }
        $stmt = $pdo->prepare("SELECT post_id FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch();
        header("Location: ../detail.php?id=" . ($comment['post_id'] ?? 0));
        exit();
        
    } elseif ($action === 'delete') {
        $comment_id = $_POST['comment_id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        
        if ($comment_id) {
            $stmt = $pdo->prepare("SELECT post_id FROM comments WHERE id = ?");
            $stmt->execute([$comment_id]);
            $comment = $stmt->fetch();
            
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
            $stmt->execute([$comment_id, $user_id]);
        }
        header("Location: ../detail.php?id=" . ($comment['post_id'] ?? 0));
        exit();
    }
} elseif ($method === 'GET') {
    if ($action === 'list' && isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
        $stmt = $pdo->prepare("SELECT c.*, u.username, u.profile_pic FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at DESC");
        $stmt->execute([$post_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($comments);
        exit();
    }
}
?>
