<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$user_id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($method == "GET") {
    if ($user_id != null) {
        $query = "SELECT id, username, name, bio, location, profile_pic, header_pic, profile_pos, header_pos, is_anonymous, created_at FROM users WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && !empty($data['is_anonymous'])) {
            if ($data['id'] != $user['id']) {
                $data['username'] = 'anonim';
                $data['name'] = 'Akun Anonim';
                $data['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
                $data['bio'] = 'Akun ini dalam mode anonim privat.';
            }
        }
    } else {
        if (isset($_GET['search']) && trim($_GET['search']) !== '') {
            $kw = "%" . trim($_GET['search']) . "%";
            $query = "SELECT id, username, name, bio, location, profile_pic, header_pic, profile_pos, header_pos, is_anonymous, created_at FROM users WHERE username LIKE ? OR name LIKE ? ORDER BY username ASC LIMIT 15";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$kw, $kw]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $query = "SELECT id, username, name, bio, location, profile_pic, header_pic, profile_pos, header_pos, is_anonymous, created_at FROM users ORDER BY username ASC";
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach ($data as &$row) {
            if (!empty($row['is_anonymous']) && $row['id'] != $user['id']) {
                $row['username'] = 'anonim';
                $row['name'] = 'Akun Anonim';
                $row['profile_pic'] = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
                $row['bio'] = 'Akun ini dalam mode anonim privat.';
            }
        }
    }

    echo json_encode($data);
} 
else if ($method == "PUT") {
    if ($user_id == null || $user_id != $user['id']) {
        echo json_encode(["error" => "Anda tidak berhak mengubah profil ini"]);
        exit;
    }

    $input = json_decode(file_get_contents("php://input"), true);
    $allowed_fields = ['username', 'name', 'bio', 'location', 'profile_pic', 'header_pic', 'profile_pos', 'header_pos', 'is_anonymous'];
    $update_data = [];

    foreach ($allowed_fields as $field) {
        if (isset($input[$field])) {
            $update_data[$field] = $input[$field];
        }
    }

    if (empty($update_data)) {
        echo json_encode(["error" => "Tidak ada data yang diperbarui"]);
        exit;
    }

    $set_clause = implode(", ", array_map(function($k) { return "$k = ?"; }, array_keys($update_data)));
    $values = array_values($update_data);
    $values[] = $user_id; 

    $stmt = $pdo->prepare("UPDATE users SET $set_clause WHERE id = ?");
    $stmt->execute($values);

    // Update PHP Session agar UI langsung tersinkronisasi
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($update_data['username']) && $update_data['username'] !== '') {
        $_SESSION['username'] = $update_data['username'];
    }
    if (isset($update_data['name']) && $update_data['name'] !== '') {
        $_SESSION['name'] = $update_data['name'];
    }
    if (isset($update_data['profile_pic']) && $update_data['profile_pic'] !== '') {
        $_SESSION['profile_pic'] = $update_data['profile_pic'];
    }
    if (isset($update_data['profile_pos'])) {
        $_SESSION['profile_pos'] = $update_data['profile_pos'];
    }
    if (isset($update_data['header_pos'])) {
        $_SESSION['header_pos'] = $update_data['header_pos'];
    }
    if (isset($update_data['is_anonymous'])) {
        $_SESSION['is_anonymous'] = intval($update_data['is_anonymous']);
    }

    echo json_encode(["success" => true, "message" => "Profil berhasil diperbarui"]);
} 
else {
    echo json_encode(["error" => "Metode tidak diizinkan"]);
}
?>