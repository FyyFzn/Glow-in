<?php
include "header.php";
$action = $_GET["action"] ?? "list";

if ($action === "delete") {
    $id = $_GET["id"];
    $stmt = $pdo->prepare("DELETE FROM schedules WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: schedules.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $keys = array_keys($_POST);
    $fields = implode(", ", $keys);
    $placeholders = implode(", ", array_fill(0, count($keys), "?"));
    $values = array_values($_POST);
    
    // Jika role spesialis, kita selipkan ID spesialis agar aman (contoh sederhana)
    $stmt = $pdo->prepare("INSERT INTO schedules ($fields) VALUES ($placeholders)");
    $stmt->execute($values);
    header("Location: schedules.php");
    exit;
}

echo "<h2>Kelola Schedules</h2>";

echo "<h3>Tambah Data</h3>";
echo "<form method='POST'>";
foreach (['available_date','start_time','end_time','status'] as $col) {
    echo "<div class='form-group'><label>$col</label><input type='text' name='$col' required></div>";
}
echo "<button type='submit' class='btn'>Simpan</button>";
echo "</form>";

$stmt = $pdo->query("SELECT * FROM schedules ORDER BY id DESC");
echo "<table><tr><th>id</th><th>available_date</th><th>start_time</th><th>end_time</th><th>status</th><th>Aksi</th></tr>";
while ($row = $stmt->fetch()) {
    echo "<tr>";
    foreach (['id','available_date','start_time','end_time','status'] as $col) {
        echo "<td>" . htmlspecialchars($row[$col] ?? "") . "</td>";
    }
    echo "<td><a href='?action=delete&id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Hapus?\")'>Hapus</a></td>";
    echo "</tr>";
}
echo "</table>";

include "footer.php";
?>