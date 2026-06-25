<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$post_id = isset($_GET["post_id"]) ? $_GET["post_id"] : null;

if ($method == "GET") {
    if ($post_id != null) {
        $stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$user['id'], $post_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $query = "
            SELECT l.*, p.content, p.created_at as post_created_at, u.username, u.profile_pic 
            FROM likes l 
            JOIN posts p ON l.post_id = p.id 
            JOIN users u ON p.user_id = u.id 
            WHERE l.user_id = ? 
            ORDER BY l.created_at DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user['id']]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($data);
} 
else if ($method == "POST") {
    $stmt = $pdo->prepare("INSERT IGNORE INTO likes (user_id, post_id) VALUES (?, ?)");
    $stmt->execute([$user['id'], $post_id]);

    $postStmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
    $postStmt->execute([$post_id]);
    $postOwner = $postStmt->fetchColumn();
    if ($postOwner && $postOwner != $user['id']) {
        $notifStmt = $pdo->prepare("INSERT INTO notifications (user_id, actor_id, type, reference_id) VALUES (?, ?, 'like', ?)");
        $notifStmt->execute([$postOwner, $user['id'], $post_id]);
    }

    $count_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
    $count_stmt->execute([$post_id]);
    $count = $count_stmt->fetch(PDO::FETCH_ASSOC)['count'];

    echo json_encode(["success" => true, "message" => "Berhasil menyukai postingan", "count" => $count]);
} 
else if ($method == "DELETE") {
    $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
    $stmt->execute([$user['id'], $post_id]);

    $count_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
    $count_stmt->execute([$post_id]);
    $count = $count_stmt->fetch(PDO::FETCH_ASSOC)['count'];

    echo json_encode(["success" => true, "message" => "Batal menyukai postingan", "count" => $count]);
}
?>