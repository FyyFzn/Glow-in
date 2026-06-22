<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "GET") {
        $stmt = $pdo->query("SELECT id, username, profile_pic, points FROM users ORDER BY points DESC, created_at ASC LIMIT 5");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    } else {
        throw new Exception("Method not allowed");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
