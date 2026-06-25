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

        if ($data && !empty($data['is_anonymous'])) {
            $data['username'] = 'Anonim';
            $data['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
            if ($data['user_id'] != $user['id']) {
                $data['user_id'] = 0;
            }
        }
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

        if (is_array($data)) {
            foreach ($data as &$row) {
                if (!empty($row['is_anonymous'])) {
                    $row['username'] = 'Anonim';
                    $row['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
                    if ($row['user_id'] != $user['id']) {
                        $row['user_id'] = 0;
                    }
                }
            }
            unset($row);
        }
    }

    echo json_encode($data);
} 
else if ($method == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $user_id = $input['user_id'];
    $content = $input['content'];
    $is_anonymous = isset($input['is_anonymous']) ? intval($input['is_anonymous']) : 0;

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, is_anonymous) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $content, $is_anonymous]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil dibuat"]);
} 
else if ($method == "PUT") {
    $input = json_decode(file_get_contents("php://input"), true);

    $content = $input['content'];
    $is_anonymous = isset($input['is_anonymous']) ? intval($input['is_anonymous']) : 0;

    $stmt = $pdo->prepare("UPDATE posts SET content = ?, is_anonymous = ? WHERE id = ?");
    $stmt->execute([$content, $is_anonymous, $id]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil diedit"]);
} 
else if ($method == "DELETE") {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil dihapus"]);
}
?>