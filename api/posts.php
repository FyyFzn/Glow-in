<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$id = $_GET["id"] ?? null;

try {
    if ($method === "GET") {
        if ($id) {
            $stmt = $pdo->prepare("
                SELECT posts.*, users.username, users.profile_pic,
                (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.id = ?
            ");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query("
                SELECT posts.*, users.username, users.profile_pic,
                (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count
                FROM posts
                JOIN users ON posts.user_id = users.id
                ORDER BY posts.created_at DESC
            ");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($data);
    } 
    elseif ($method === "POST") {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['user_id']) || !isset($input['content'])) {
            throw new Exception("Missing required fields: user_id and content are required");
        }
        
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->execute([$input['user_id'], $input['content']]);
        $postId = $pdo->lastInsertId();
        
        echo json_encode(["success" => true, "message" => "Post created successfully", "id" => $postId]);
    }
    elseif ($method === "PUT") {
        if (!$id) throw new Exception("ID required for PUT");
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['content'])) {
            throw new Exception("Content is required");
        }
        
        $stmt = $pdo->prepare("UPDATE posts SET content = ? WHERE id = ?");
        $stmt->execute([$input['content'], $id]);
        
        echo json_encode(["success" => true, "message" => "Post updated successfully"]);
    }
    elseif ($method === "DELETE") {
        if (!$id) throw new Exception("ID required for DELETE");
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Post deleted successfully"]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>