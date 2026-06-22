<?php
require_once "../config.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

function checkApiKey() {
    global $pdo;
    $headers = apache_request_headers();
    $auth = $headers["Authorization"] ?? "";
    $api_key = str_replace("Bearer ", "", $auth);
    if (!$api_key) $api_key = $_GET["api_key"] ?? "";
    
    if (empty($api_key)) {
        http_response_code(401);
        echo json_encode(["error" => "API Key is required"]);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT id, role FROM users WHERE api_key = ?");
    $stmt->execute([$api_key]);
    $user = $stmt->fetch();
    
    if (!$user) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid API Key"]);
        exit;
    }
    return $user;
}
?>