<?php
require_once 'middleware.php';
$user = checkApiKey();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $action = $_GET['action'] ?? '';
        if ($action === 'realtime') {
            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
            $countStmt->execute([$user['id']]);
            $unreadCount = (int)$countStmt->fetchColumn();

            $latestStmt = $pdo->prepare("
                SELECT n.id, n.type, n.created_at, u.username, u.name, u.profile_pic 
                FROM notifications n 
                JOIN users u ON n.actor_id = u.id 
                WHERE n.user_id = ? AND n.is_read = 0 
                ORDER BY n.id DESC 
                LIMIT 1
            ");
            $latestStmt->execute([$user['id']]);
            $latest = $latestStmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                "unread_count" => $unreadCount,
                "latest" => $latest ?: null
            ]);
            exit;
        }

        $stmt = $pdo->prepare("
            SELECT n.*, u.username, u.name, u.profile_pic 
            FROM notifications n 
            JOIN users u ON n.actor_id = u.id 
            WHERE n.user_id = ? 
            ORDER BY n.created_at DESC 
            LIMIT 20
        ");
        $stmt->execute([$user['id']]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } else if ($method === 'PUT') {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        echo json_encode(['success' => true, 'message' => 'All notifications marked as read']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
