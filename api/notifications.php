<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "GET") {
        $stmt = $pdo->prepare("SELECT n.*, u.username, u.profile_pic FROM notifications n JOIN users u ON n.actor_id = u.id WHERE n.user_id = ? ORDER BY n.created_at DESC LIMIT 20");
        $stmt->execute([$user['id']]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } 
    elseif ($method === "PUT") {
        // Mark all as read
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        echo json_encode(["success" => true, "message" => "Notifications marked as read"]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
