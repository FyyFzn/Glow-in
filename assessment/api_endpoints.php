<?php
require_once "../config.php";
header("Content-Type: application/json");

// 1. Cek keberadaan API Key di URL
if (!isset($_GET['api_key'])) {
    echo json_encode(["error" => "API Key tidak ditemukan"]);
    exit;
}

$api_key = $_GET['api_key'];

// 2. Validasi API Key ke database user
$query_user = "SELECT id FROM users WHERE api_key = ?";
$stmt_user = $pdo->prepare($query_user);
$stmt_user->execute([$api_key]);
$user = $stmt_user->fetch();

if (!$user) {
    echo json_encode(["error" => "API Key tidak valid"]);
    exit;
}
$user_id = $user['id']; // Simpan ID user untuk proses insert/update/delete

// 3. Ambil parameter endpoint dan method HTTP
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : 'posts';
$method = $_SERVER['REQUEST_METHOD'];

// Ambil input JSON dari body request (untuk method POST / PUT)
$input_json = file_get_contents("php://input");
$data = json_decode($input_json, true);

// ==========================================
// ENDPOINT 1: POSTS (Fokus Utama CRUD)
// ==========================================
if ($endpoint == 'posts') {
    
    // READ (Tampilkan Semua Data)
    if ($method == 'GET') {
        $query = "SELECT * FROM posts ORDER BY id DESC";
        $posts = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($posts);
    } 
    // CREATE (Tambah Data Baru)
    else if ($method == 'POST') {
        $content = $data['content'];
        $query = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id, $content]);
        echo json_encode(["status" => "Berhasil tambah postingan"]);
    } 
    // UPDATE (Ubah Data / Edit)
    else if ($method == 'PUT') {
        $post_id = $data['id'];
        $content = $data['content'];
        $query = "UPDATE posts SET content = ? WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$content, $post_id, $user_id]);
        echo json_encode(["status" => "Berhasil edit postingan"]);
    }
    // DELETE (Hapus Data)
    else if ($method == 'DELETE') {
        $post_id = $_GET['id'];
        $query = "DELETE FROM posts WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$post_id, $user_id]);
        echo json_encode(["status" => "Berhasil hapus postingan"]);
    }
} 
// ==========================================
// ENDPOINT 2: CATEGORIES (Syarat 2 Endpoint)
// ==========================================
else if ($endpoint == 'categories') {
    // Hanya formalitas memenuhi syarat "Minimal 2 Endpoint" dari dosen
    if ($method == 'GET') {
        $query = "SELECT * FROM categories";
        $categories = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categories);
    }
} 
// Jika endpoint salah
else {
    echo json_encode(["error" => "Endpoint tidak ditemukan"]);
}
?>
