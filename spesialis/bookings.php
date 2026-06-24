<?php
include "header.php";
$action = $_GET["action"] ?? "list";
$edit_data = null;

if ($action === "delete") {
    $id = $_GET["id"];

    $stmt = $pdo->prepare("DELETE b FROM bookings b JOIN schedules s ON b.schedule_id = s.id WHERE b.id = ? AND s.spesialis_id = ?");
    $stmt->execute([$id, $spesialis_id]);
    header("Location: bookings.php");
    exit;
}

if ($action === "edit") {
    $id = $_GET["id"];
    $stmt = $pdo->prepare("SELECT b.* FROM bookings b JOIN schedules s ON b.schedule_id = s.id WHERE b.id = ? AND s.spesialis_id = ?");
    $stmt->execute([$id, $spesialis_id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postinger_id = $_POST["postinger_id"];
    $schedule_id = $_POST["schedule_id"];
    $status = $_POST["status"];
    $notes = $_POST["notes"];

    $check_schedule = $pdo->prepare("SELECT id FROM schedules WHERE id = ? AND spesialis_id = ?");
    $check_schedule->execute([$schedule_id, $spesialis_id]);
    if ($check_schedule->rowCount() > 0) {
        if (isset($_POST["id"]) && !empty($_POST["id"])) {

            $id = $_POST["id"];
            $stmt = $pdo->prepare("UPDATE bookings SET postinger_id = ?, schedule_id = ?, status = ?, notes = ? WHERE id = ?");
            $stmt->execute([$postinger_id, $schedule_id, $status, $notes, $id]);
        } else {

            $stmt = $pdo->prepare("INSERT INTO bookings (postinger_id, schedule_id, status, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$postinger_id, $schedule_id, $status, $notes]);
        }
    }
    header("Location: bookings.php");
    exit;
}

echo "<h2>Kelola Transaksi Konsultasi (Bookings)</h2>";

echo "<h3>" . ($edit_data ? "Edit Booking" : "Tambah Booking Manual") . "</h3>";
echo "<form method='POST'>";
if ($edit_data) {
    echo "<input type='hidden' name='id' value='" . htmlspecialchars($edit_data["id"]) . "'>";
}

$postinger_val = $edit_data ? htmlspecialchars($edit_data["postinger_id"]) : "";
$schedule_val = $edit_data ? htmlspecialchars($edit_data["schedule_id"]) : "";
$status_val = $edit_data ? htmlspecialchars($edit_data["status"]) : "menunggu";
$notes_val = $edit_data ? htmlspecialchars($edit_data["notes"]) : "";

echo "<div class='form-group'><label>ID User (Postinger)</label><input type='number' name='postinger_id' value='$postinger_val' required></div>";

echo "<div class='form-group'><label>Pilih Jadwal</label>";
echo "<select name='schedule_id' required>";
$schedules_stmt = $pdo->prepare("SELECT id, available_date, start_time FROM schedules WHERE spesialis_id = ? ORDER BY available_date DESC");
$schedules_stmt->execute([$spesialis_id]);
while($sch = $schedules_stmt->fetch()){
    $sel = ($schedule_val == $sch['id']) ? "selected" : "";
    echo "<option value='".$sch['id']."' $sel>Jadwal #".$sch['id']." - ".$sch['available_date']." (".$sch['start_time'].")</option>";
}
echo "</select></div>";

echo "<div class='form-group'><label>Status Booking</label>";
echo "<select name='status' required>";
$options = ['menunggu', 'disetujui', 'selesai', 'dibatalkan'];
foreach ($options as $opt) {
    $selected = ($status_val === $opt) ? "selected" : "";
    echo "<option value='$opt' $selected>" . ucfirst($opt) . "</option>";
}
echo "</select></div>";

echo "<div class='form-group'><label>Catatan Tambahan</label><textarea name='notes'>$notes_val</textarea></div>";

echo "<button type='submit' class='btn'>Simpan Booking</button>";
if ($edit_data) {
    echo " <a href='bookings.php' class='btn btn-danger'>Batal</a>";
}
echo "</form>";

echo "<h3>Daftar Booking Konsultasi Anda</h3>";
$stmt = $pdo->prepare("
    SELECT b.*, u.username as client_name, s.available_date, s.start_time 
    FROM bookings b 
    JOIN schedules s ON b.schedule_id = s.id 
    JOIN users u ON b.postinger_id = u.id 
    WHERE s.spesialis_id = ? 
    ORDER BY b.id DESC
");
$stmt->execute([$spesialis_id]);
echo "<table><tr><th>ID</th><th>Client</th><th>Jadwal</th><th>Status</th><th>Catatan</th><th>Aksi</th></tr>";
while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>" . $row["id"] . "</td>";
    echo "<td>" . htmlspecialchars($row["client_name"]) . " (ID: ".$row["postinger_id"].")</td>";
    echo "<td>" . htmlspecialchars($row["available_date"]) . " " . htmlspecialchars($row["start_time"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
    echo "<td>" . htmlspecialchars($row["notes"]) . "</td>";
    echo "<td>
            <a href='?action=edit&id=" . $row["id"] . "' class='btn'>Edit</a>
            <a href='?action=delete&id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Hapus booking ini?\")'>Hapus</a>
          </td>";
    echo "</tr>";
}
echo "</table>";

include "footer.php";
?>