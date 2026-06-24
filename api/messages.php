<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "GET") {
        $chatWith = $_GET['chat_with'] ?? null;

        if ($chatWith) {

            $stmt = $pdo->prepare("SELECT m.*, u.username, u.profile_pic FROM messages m JOIN users u ON m.sender_id = u.id WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?) ORDER BY m.created_at ASC");
            $stmt->execute([$user['id'], $chatWith, $chatWith, $user['id']]);
        } else {

            $stmt = $pdo->prepare("SELECT m.*, u.username, u.profile_pic FROM messages m JOIN users u ON (m.sender_id = u.id AND m.sender_id != ?) OR (m.receiver_id = u.id AND m.receiver_id != ?) WHERE m.receiver_id = ? OR m.sender_id = ? ORDER BY m.created_at DESC");
            $stmt->execute([$user['id'], $user['id'], $user['id'], $user['id']]);
        }
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } 
    elseif ($method === "POST") {
        $input = json_decode(file_get_contents("php://input"), true);
        if(!isset($input['receiver_id']) || !isset($input['message'])) throw new Exception("Missing required fields");

        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $input['receiver_id'], $input['message']]);
        echo json_encode(["success" => true, "message" => "Message sent"]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
