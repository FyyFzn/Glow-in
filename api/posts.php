<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($method == "GET") {
    if ($id != null) {
        $query = "
            SELECT posts.*, users.username, users.profile_pic,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.id = ?
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $query = "
            SELECT posts.*, users.username, users.profile_pic,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            ORDER BY posts.created_at DESC
        ";
        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($data);
} 
else if ($method == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $user_id = $input['user_id'];
    $content = $input['content'];

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->execute([$user_id, $content]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil dibuat"]);
} 
else if ($method == "PUT") {
    $input = json_decode(file_get_contents("php://input"), true);

    $content = $input['content'];

    $stmt = $pdo->prepare("UPDATE posts SET content = ? WHERE id = ?");
    $stmt->execute([$content, $id]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil diedit"]);
} 
else if ($method == "DELETE") {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil dihapus"]);
}
?>