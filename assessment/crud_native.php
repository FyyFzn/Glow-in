<?php
require_once "../config.php";

// Anggap user ID yang sedang login adalah 1 (untuk penyederhanaan tes CRUD)
$user_id_aktif = 1; 

// ==========================================
// 1. PROSES AKSI DARI FORM (CREATE, UPDATE, DELETE)
// ==========================================
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Aksi Tambah Postingan (Create)
    if ($action == 'create') {
        $content = $_POST['content'];
        
        $query = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id_aktif, $content]);
    } 
    // Aksi Ubah Postingan (Update)
    else if ($action == 'update') {
        $id = $_POST['id'];
        $content = $_POST['content'];
        
        $query = "UPDATE posts SET content = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$content, $id]);
    } 
    // Aksi Hapus Postingan (Delete)
    else if ($action == 'delete') {
        $id = $_POST['id'];
        
        $query = "DELETE FROM posts WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
    }
    
    // Refresh halaman setelah aksi selesai
    header("Location: crud_native.php");
    exit;
}

// ==========================================
// 2. MENGAMBIL DATA UNTUK DITAMPILKAN
// ==========================================

// Ambil semua data postingan (Read All)
$query_select = "SELECT * FROM posts ORDER BY id DESC";
$stmt_select = $pdo->query($query_select);
$items = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

// Cek apakah ada data yang dipilih untuk di-edit (Read One)
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $query_edit = "SELECT * FROM posts WHERE id = ?";
    $stmt_edit = $pdo->prepare($query_edit);
    $stmt_edit->execute([$id_edit]);
    $edit_data = $stmt_edit->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Postingan Native</title>
</head>
<body>

<h1>Manajemen Postingan (Full PHP Native)</h1>
<hr>

<!-- FORM INPUT / EDIT DATA -->
<h3>Form Postingan</h3>
<form method="POST">
    <!-- Penanda aksi yang dikirim (create / update) -->
    <?php if ($edit_data != null): ?>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
    <?php else: ?>
        <input type="hidden" name="action" value="create">
    <?php endif; ?>

    <label>Isi Postingan:</label><br>
    <textarea name="content" rows="4" cols="50" required><?= isset($edit_data['content']) ? $edit_data['content'] : '' ?></textarea>
    <br><br>

    <button type="submit">Simpan Postingan</button>
    <?php if ($edit_data != null): ?>
        <a href="crud_native.php">Batal Edit</a>
    <?php endif; ?>
</form>

<br>

<!-- TABEL MENAMPILKAN DATA -->
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Isi Konten</th>
        <th>Tanggal Dibuat</th>
        <th>Aksi</th>
    </tr>
    <?php foreach($items as $row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['user_id'] ?></td>
        <td><?= htmlspecialchars($row['content']) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
            <!-- Tombol Edit -->
            <a href="?edit=<?= $row['id'] ?>">Edit</a>
            
            <!-- Tombol Hapus -->
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
