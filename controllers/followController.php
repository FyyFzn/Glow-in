<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$target_user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;

if ($method == "GET") {
    if ($target_user_id != null) {
        $stmt = $pdo->prepare("SELECT * FROM follows WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$user['id'], $target_user_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } 
    else if (isset($_GET["type"])) {
        if ($_GET["type"] == "followers") {
            $query = "
                SELECT f.*, u.username, u.name, u.profile_pic,
                       EXISTS(SELECT 1 FROM follows f2 WHERE f2.follower_id = ? AND f2.following_id = u.id) AS is_following
                FROM follows f
                JOIN users u ON f.follower_id = u.id
                WHERE f.following_id = ?
                ORDER BY f.created_at DESC
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user['id'], $user['id']]);
        } 
        else {
            $query = "
                SELECT f.*, u.username, u.name, u.profile_pic, 1 AS is_following
                FROM follows f
                JOIN users u ON f.following_id = u.id
                WHERE f.follower_id = ?
                ORDER BY f.created_at DESC
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user['id']]);
        }
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $data = []; 
    }

    echo json_encode($data);
} 
else if ($method == "POST") {
    $stmt = $pdo->prepare("INSERT IGNORE INTO follows (follower_id, following_id) VALUES (?, ?)");
    $stmt->execute([$user['id'], $target_user_id]);

    if ($target_user_id && $target_user_id != $user['id']) {
        $notifStmt = $pdo->prepare("INSERT INTO notifications (user_id, actor_id, type) VALUES (?, ?, 'follow')");
        $notifStmt->execute([$target_user_id, $user['id']]);
    }

    echo json_encode(["success" => true, "message" => "Berhasil mengikuti pengguna ini"]);
} 
else if ($method == "DELETE") {
    $stmt = $pdo->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?");
    $stmt->execute([$user['id'], $target_user_id]);

    echo json_encode(["success" => true, "message" => "Berhenti mengikuti pengguna ini"]);
}
?>