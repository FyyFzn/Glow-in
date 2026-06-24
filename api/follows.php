<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$target_user_id = $_GET["user_id"] ?? null;

try {
    if ($method === "GET") {
        if ($target_user_id) {
            // Check if current user follows target user
            $stmt = $pdo->prepare("SELECT * FROM follows WHERE follower_id = ? AND following_id = ?");
            $stmt->execute([$user['id'], $target_user_id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } 
        elseif (isset($_GET["type"])) {
            if ($_GET["type"] === "followers") {
                // Get followers of current user and whether current user follows them back
                $stmt = $pdo->prepare("
                    SELECT f.*, u.username, u.name, u.profile_pic,
                           EXISTS(SELECT 1 FROM follows f2 WHERE f2.follower_id = ? AND f2.following_id = u.id) AS is_following
                    FROM follows f
                    JOIN users u ON f.follower_id = u.id
                    WHERE f.following_id = ?
                    ORDER BY f.created_at DESC
                ");
                $stmt->execute([$user['id'], $user['id']]);
            } 
            else {
                // Get following of current user
                $stmt = $pdo->prepare("
                    SELECT f.*, u.username, u.name, u.profile_pic, 1 AS is_following
                    FROM follows f
                    JOIN users u ON f.following_id = u.id
                    WHERE f.follower_id = ?
                    ORDER BY f.created_at DESC
                ");
                $stmt->execute([$user['id']]);
            }
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($data);
    } 
    elseif ($method === "POST") {
        // Follow a user
        if (!$target_user_id) throw new Exception("User ID required");
        if ($target_user_id == $user['id']) throw new Exception("Cannot follow yourself");
        
        $stmt = $pdo->prepare("INSERT INTO follows (follower_id, following_id) VALUES (?, ?)");
        $stmt->execute([$user['id'], $target_user_id]);
        
        echo json_encode(["success" => true, "message" => "Followed"]);
    }
    elseif ($method === "DELETE") {
        // Unfollow a user
        if (!$target_user_id) throw new Exception("User ID required");
        
        $stmt = $pdo->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$user['id'], $target_user_id]);
        
        echo json_encode(["success" => true, "message" => "Unfollowed"]);
    }
    else {
        throw new Exception("Method not allowed");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>