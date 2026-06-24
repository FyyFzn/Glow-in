<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$post_id = $_GET["post_id"] ?? null;

try {
    if ($method === "GET") {
        if ($post_id) {
            // Check if current user liked this post
            $stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$user['id'], $post_id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Get all likes for current user
            $stmt = $pdo->prepare("
                SELECT l.*, p.content, p.created_at as post_created_at, u.username, u.profile_pic 
                FROM likes l 
                JOIN posts p ON l.post_id = p.id 
                JOIN users u ON p.user_id = u.id 
                WHERE l.user_id = ? 
                ORDER BY l.created_at DESC
            ");
            $stmt->execute([$user['id']]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($data);
    } 
    elseif ($method === "POST") {
        // Like a post
        if (!$post_id) throw new Exception("Post ID required");
        
        $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
        $stmt->execute([$user['id'], $post_id]);
        
        // Get new like count
        $count_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
        $count_stmt->execute([$post_id]);
        $count = $count_stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        echo json_encode(["success" => true, "message" => "Liked", "count" => $count]);
    }
    elseif ($method === "DELETE") {
        // Unlike a post
        if (!$post_id) throw new Exception("Post ID required");
        
        $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$user['id'], $post_id]);
        
        // Get new like count
        $count_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
        $count_stmt->execute([$post_id]);
        $count = $count_stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        echo json_encode(["success" => true, "message" => "Unliked", "count" => $count]);
    }
    else {
        throw new Exception("Method not allowed");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>