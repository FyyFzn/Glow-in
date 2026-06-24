<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($method == "GET") {
    if ($id != null) {
        $stmt = $pdo->prepare("SELECT * FROM schedules WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT * FROM schedules ORDER BY available_date DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($data);
} 
else if ($method == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $spesialis_id = $input['spesialis_id'];
    $available_date = $input['available_date'];
    $start_time = $input['start_time'];
    $end_time = $input['end_time'];
    $status = isset($input['status']) ? $input['status'] : 'tersedia';

    $query = "INSERT INTO schedules (spesialis_id, available_date, start_time, end_time, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$spesialis_id, $available_date, $start_time, $end_time, $status]);

    echo json_encode(["success" => true, "message" => "Jadwal berhasil ditambahkan"]);
} 
else if ($method == "PUT") {
    $input = json_decode(file_get_contents("php://input"), true);

    $available_date = $input['available_date'];
    $start_time = $input['start_time'];
    $end_time = $input['end_time'];
    $status = $input['status'];

    $query = "UPDATE schedules SET available_date = ?, start_time = ?, end_time = ?, status = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$available_date, $start_time, $end_time, $status, $id]);

    echo json_encode(["success" => true, "message" => "Jadwal berhasil diubah"]);
} 
else if ($method == "DELETE") {
    $stmt = $pdo->prepare("DELETE FROM schedules WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Jadwal berhasil dihapus"]);
}
?>