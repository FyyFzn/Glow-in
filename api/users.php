<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$user_id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($method == "GET") {
    if ($user_id != null) {
        $query = "SELECT id, username, name, bio, location, profile_pic, header_pic, created_at FROM users WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $query = "SELECT id, username, name, bio, location, profile_pic, header_pic, created_at FROM users ORDER BY username ASC";
        $stmt = $pdo->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($data);
} 
else if ($method == "PUT") {
    if ($user_id == null || $user_id != $user['id']) {
        echo json_encode(["error" => "Anda tidak berhak mengubah profil ini"]);
        exit;
    }

    $input = json_decode(file_get_contents("php://input"), true);
    $allowed_fields = ['name', 'bio', 'location', 'profile_pic', 'header_pic'];
    $update_data = [];

    foreach ($allowed_fields as $field) {
        if (isset($input[$field])) {
            $update_data[$field] = $input[$field];
        }
    }

    if (empty($update_data)) {
        echo json_encode(["error" => "Tidak ada data yang diubah"]);
        exit;
    }

    $set_clause = implode(", ", array_map(function($k) { return "$k = ?"; }, array_keys($update_data)));
    $values = array_values($update_data);
    $values[] = $user_id; 

    $stmt = $pdo->prepare("UPDATE users SET $set_clause WHERE id = ?");
    $stmt->execute($values);

    echo json_encode(["success" => true, "message" => "Profil berhasil diperbarui"]);
} 
else {
    echo json_encode(["error" => "Metode tidak diizinkan"]);
}
?>