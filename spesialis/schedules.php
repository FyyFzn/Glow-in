<?php
include "header.php";
$action = $_GET["action"] ?? "list";
$edit_data = null;

// Handle Delete
if ($action === "delete") {
    $id = $_GET["id"];
    // Ensure they only delete their own schedule
    $stmt = $pdo->prepare("DELETE FROM schedules WHERE id = ? AND spesialis_id = ?");
    $stmt->execute([$id, $spesialis_id]);
    header("Location: schedules.php");
    exit;
}

// Handle Edit (get data for form)
if ($action === "edit") {
    $id = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM schedules WHERE id = ? AND spesialis_id = ?");
    $stmt->execute([$id, $spesialis_id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle POST (Create or Update)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $available_date = $_POST["available_date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $status = $_POST["status"];

    if (isset($_POST["id"]) && !empty($_POST["id"])) {
        // Update
        $id = $_POST["id"];
        $stmt = $pdo->prepare("UPDATE schedules SET available_date = ?, start_time = ?, end_time = ?, status = ? WHERE id = ? AND spesialis_id = ?");
        $stmt->execute([$available_date, $start_time, $end_time, $status, $id, $spesialis_id]);
    } else {
        // Create
        $stmt = $pdo->prepare("INSERT INTO schedules (spesialis_id, available_date, start_time, end_time, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$spesialis_id, $available_date, $start_time, $end_time, $status]);
    }
    header("Location: schedules.php");
    exit;
}

echo "<h2>Kelola Jadwal Konsultasi (Schedules)</h2>";

echo "<h3>" . ($edit_data ? "Edit Jadwal" : "Tambah Jadwal Baru") . "</h3>";
echo "<form method='POST'>";
if ($edit_data) {
    echo "<input type='hidden' name='id' value='" . htmlspecialchars($edit_data["id"]) . "'>";
}

$date_val = $edit_data ? htmlspecialchars($edit_data["available_date"]) : "";
$start_val = $edit_data ? htmlspecialchars($edit_data["start_time"]) : "";
$end_val = $edit_data ? htmlspecialchars($edit_data["end_time"]) : "";
$status_val = $edit_data ? htmlspecialchars($edit_data["status"]) : "tersedia";

echo "<div class='form-group'><label>Tanggal Tersedia</label><input type='date' name='available_date' value='$date_val' required></div>";
echo "<div class='form-group'><label>Waktu Mulai</label><input type='time' name='start_time' value='$start_val' required></div>";
echo "<div class='form-group'><label>Waktu Selesai</label><input type='time' name='end_time' value='$end_val' required></div>";

echo "<div class='form-group'><label>Status</label>";
echo "<select name='status' required>";
$options = ['tersedia', 'dibooking'];
foreach ($options as $opt) {
    $selected = ($status_val === $opt) ? "selected" : "";
    echo "<option value='$opt' $selected>" . ucfirst($opt) . "</option>";
}
echo "</select></div>";

echo "<button type='submit' class='btn'>Simpan Jadwal</button>";
if ($edit_data) {
    echo " <a href='schedules.php' class='btn btn-danger'>Batal</a>";
}
echo "</form>";

echo "<h3>Daftar Jadwal Anda</h3>";
$stmt = $pdo->prepare("SELECT * FROM schedules WHERE spesialis_id = ? ORDER BY available_date DESC, start_time ASC");
$stmt->execute([$spesialis_id]);
echo "<table><tr><th>ID</th><th>Tanggal</th><th>Mulai</th><th>Selesai</th><th>Status</th><th>Aksi</th></tr>";
while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . htmlspecialchars($row["available_date"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["start_time"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["end_time"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
    echo "<td>
            <a href='?action=edit&id=" . $row["id"] . "' class='btn'>Edit</a>
            <a href='?action=delete&id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus jadwal ini?\")'>Hapus</a>
          </td>";
    echo "</tr>";
}
echo "</table>";

include "footer.php";
?>