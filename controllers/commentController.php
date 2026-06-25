<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];

$id = isset($_GET["id"]) ? $_GET["id"] : null;
$post_id = isset($_GET["post_id"]) ? $_GET["post_id"] : null;

if ($method == "GET") {
    if ($id != null) {
        $query = "SELECT comments.*, COALESCE(NULLIF(users.name, ''), users.username) AS username, users.profile_pic, users.is_anonymous FROM comments JOIN users ON comments.user_id = users.id WHERE comments.id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data && !empty($data['is_anonymous'])) {
            if ($data['user_id'] != $user['id']) {
                $data['username'] = 'Anonim';
                $data['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
            }
        }
    } 
    else if ($post_id != null) {
        $query = "SELECT comments.*, COALESCE(NULLIF(users.name, ''), users.username) AS username, users.profile_pic, users.is_anonymous FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$post_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    else {
        $query = "SELECT comments.*, COALESCE(NULLIF(users.name, ''), users.username) AS username, users.profile_pic, users.is_anonymous FROM comments JOIN users ON comments.user_id = users.id ORDER BY comments.created_at DESC";
        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if (is_array($data)) {
        foreach ($data as &$row) {
            if (!empty($row['is_anonymous'])) {
                if ($row['user_id'] != $user['id']) {
                    $row['username'] = 'Anonim';
                    $row['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
                }
            }
        }
        unset($row);
    }

    echo json_encode($data);
} 
else if ($method == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $user_id = $input['user_id'];
    $post_id = $input['post_id'];
    $comment_text = $input['comment_text'];

    $query = "INSERT INTO comments (user_id, post_id, comment_text) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id, $post_id, $comment_text]);

    $postStmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
    $postStmt->execute([$post_id]);
    $postOwner = $postStmt->fetchColumn();
    if ($postOwner && $postOwner != $user_id) {
        $notifStmt = $pdo->prepare("INSERT INTO notifications (user_id, actor_id, type, reference_id) VALUES (?, ?, 'comment', ?)");
        $notifStmt->execute([$postOwner, $user_id, $post_id]);
    }

    echo json_encode(["success" => true, "message" => "Komentar berhasil ditambahkan"]);
} 
else if ($method == "PUT") {
    $input = json_decode(file_get_contents("php://input"), true);
    $comment_text = $input['comment_text'];

    $query = "UPDATE comments SET comment_text = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$comment_text, $id]);

    echo json_encode(["success" => true, "message" => "Komentar berhasil diubah"]);
} 
else if ($method == "DELETE") {
    $query = "DELETE FROM comments WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Komentar berhasil dihapus"]);
}
?>