<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$id = $_GET["id"] ?? null;

try {
    if ($method === "GET") {
        if ($id) {
            $stmt = $pdo->prepare("SELECT comments.*, users.username, users.profile_pic FROM comments JOIN users ON comments.user_id = users.id WHERE comments.id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $post_id = $_GET['post_id'] ?? null;
            if ($post_id) {
                $stmt = $pdo->prepare("SELECT comments.*, users.username, users.profile_pic FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at DESC");
                $stmt->execute([$post_id]);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $pdo->query("SELECT comments.*, users.username, users.profile_pic FROM comments JOIN users ON comments.user_id = users.id ORDER BY comments.created_at DESC");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        echo json_encode($data);
    } 
    elseif ($method === "POST") {
        // Handle POST for creating comments
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Validate required fields
        if (!isset($input['user_id']) || !isset($input['post_id']) || !isset($input['comment_text'])) {
            throw new Exception("Missing required fields: user_id, post_id, and comment_text are required");
        }
        
        // Insert comment
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, comment_text) VALUES (?, ?, ?)");
        $stmt->execute([$input['user_id'], $input['post_id'], $input['comment_text']]);
        $commentId = $pdo->lastInsertId();
        
        echo json_encode(["success" => true, "message" => "Comment created successfully", "id" => $commentId]);
    }
    elseif ($method === "PUT") {
        if (!$id) throw new Exception("ID required for PUT");
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Validate
        if (!isset($input['comment_text'])) {
            throw new Exception("Comment text is required");
        }
        
        $stmt = $pdo->prepare("UPDATE comments SET comment_text = ? WHERE id = ?");
        $stmt->execute([$input['comment_text'], $id]);
        
        echo json_encode(["success" => true, "message" => "Comment updated successfully"]);
    }
    elseif ($method === "DELETE") {
        if (!$id) throw new Exception("ID required for DELETE");
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Comment deleted successfully"]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>