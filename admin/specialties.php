<?php
include "header.php";
$action = $_GET["action"] ?? "list";
$edit_data = null;

if ($action === "delete") {
    $id = $_GET["id"];
    $stmt = $pdo->prepare("DELETE FROM specialties WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: specialties.php");
    exit;
}

if ($action === "edit") {
    $id = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM specialties WHERE id = ?");
    $stmt->execute([$id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id"]) && !empty($_POST["id"])) {

        $id = $_POST["id"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $stmt = $pdo->prepare("UPDATE specialties SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $id]);
    } else {

        $name = $_POST["name"];
        $description = $_POST["description"];
        $stmt = $pdo->prepare("INSERT INTO specialties (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
    }
    header("Location: specialties.php");
    exit;
}

echo "<h2>Kelola Specialties</h2>";

echo "<h3>" . ($edit_data ? "Edit Data" : "Tambah Data") . "</h3>";
echo "<form method='POST'>";
if ($edit_data) {
    echo "<input type='hidden' name='id' value='" . htmlspecialchars($edit_data["id"]) . "'>";
}
foreach (['name','description'] as $col) {
    $value = $edit_data ? htmlspecialchars($edit_data[$col] ?? "") : "";
    echo "<div class='form-group'><label>$col</label><input type='text' name='$col' value='$value' required></div>";
}
echo "<button type='submit' class='btn'>Simpan</button>";
if ($edit_data) {
    echo " <a href='specialties.php' class='btn btn-danger'>Batal</a>";
}
echo "</form>";

$stmt = $pdo->query("SELECT * FROM specialties ORDER BY id DESC");
echo "<table><tr><th>id</th><th>name</th><th>description</th><th>Aksi</th></tr>";
while ($row = $stmt->fetch()) {
    echo "<tr>";
    foreach (['id','name','description'] as $col) {
        echo "<td>" . htmlspecialchars($row[$col] ?? "") . "</td>";
    }
    echo "<td>
            <a href='?action=edit&id=" . $row["id"] . "' class='btn'>Edit</a>
            <a href='?action=delete&id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Hapus?\")'>Hapus</a>
          </td>";
    echo "</tr>";
}
echo "</table>";

include "footer.php";
?>