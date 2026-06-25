<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($method == "GET") {
    if ($id != null) {
        $query = "
            SELECT posts.*, COALESCE(NULLIF(users.name, ''), users.username) AS username, users.profile_pic, users.is_anonymous AS user_is_anonymous,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.id = ?
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && (!empty($data['is_anonymous']) || !empty($data['user_is_anonymous']))) {
            if ($data['user_id'] != $user['id']) {
                $data['username'] = 'Anonim';
                $data['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
                $data['user_id'] = 0;
            } else {
                $data['username'] = 'Anonim (Anda)';
                $data['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
            }
        }
    } else if (isset($_GET['user_id'])) {
        $target_id = intval($_GET['user_id']);
        if ($target_id == $user['id']) {
            $query = "
                SELECT posts.*, COALESCE(NULLIF(users.name, ''), users.username) AS username, users.profile_pic, users.is_anonymous AS user_is_anonymous,
                (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.user_id = ?
                ORDER BY posts.created_at DESC
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$target_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $query = "
                SELECT posts.*, COALESCE(NULLIF(users.name, ''), users.username) AS username, users.profile_pic, users.is_anonymous AS user_is_anonymous,
                (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comment_count,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as like_count
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.user_id = ? AND posts.is_anonymous = 0 AND (users.is_anonymous IS NULL OR users.is_anonymous = 0)
                ORDER BY posts.created_at DESC
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$target_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        $query = "
            SELECT posts.*, COALESCE(NULLIF(users.name, ''), users.username) AS username, users.profile_pic, users.is_anonymous AS user_is_anonymous,
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
                if (!empty($row['is_anonymous']) || !empty($row['user_is_anonymous'])) {
                    if ($row['user_id'] != $user['id']) {
                        $row['username'] = 'Anonim';
                        $row['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
                        $row['user_id'] = 0;
                    } else {
                        $row['username'] = 'Anonim (Anda)';
                        $row['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
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
    $image = isset($input['image']) ? $input['image'] : null;
    $is_anonymous = isset($input['is_anonymous']) ? intval($input['is_anonymous']) : 0;

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, image, is_anonymous) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $content, $image, $is_anonymous]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil dibuat"]);
} 
else if ($method == "PUT") {
    $input = json_decode(file_get_contents("php://input"), true);

    $content = $input['content'];
    $image = isset($input['image']) ? $input['image'] : null;
    $is_anonymous = isset($input['is_anonymous']) ? intval($input['is_anonymous']) : 0;

    $stmt = $pdo->prepare("UPDATE posts SET content = ?, image = ?, is_anonymous = ? WHERE id = ?");
    $stmt->execute([$content, $image, $is_anonymous, $id]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil diedit"]);
} 
else if ($method == "DELETE") {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Postingan berhasil dihapus"]);
}
?>