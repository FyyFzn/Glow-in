<?php
require_once "middleware.php";

$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($method == "GET") {
    if ($id != null) {
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT * FROM bookings ORDER BY created_at DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($data);
} 
else if ($method == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $postinger_id = $input['postinger_id'];
    $schedule_id = $input['schedule_id'];
    $status = isset($input['status']) ? $input['status'] : 'menunggu';
    $notes = isset($input['notes']) ? $input['notes'] : '';

    $query = "INSERT INTO bookings (postinger_id, schedule_id, status, notes) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$postinger_id, $schedule_id, $status, $notes]);

    echo json_encode(["success" => true, "message" => "Pesanan konsultasi berhasil dibuat"]);
} 
else if ($method == "PUT") {
    $input = json_decode(file_get_contents("php://input"), true);

    $status = $input['status'];
    $notes = isset($input['notes']) ? $input['notes'] : '';

    $query = "UPDATE bookings SET status = ?, notes = ? WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$status, $notes, $id]);

    echo json_encode(["success" => true, "message" => "Status pesanan berhasil diubah"]);
} 
else if ($method == "DELETE") {
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Pesanan berhasil dihapus/dibatalkan"]);
}
?>