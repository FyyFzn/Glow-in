<?php
require_once "../config.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

function getAuthorizationHeader() {
    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            return $headers['Authorization'];
        }
        if (isset($headers['authorization'])) {
            return $headers['authorization'];
        }
    }
    // Fallback for non-Apache servers
    $headers = [];
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) == 'HTTP_') {
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
    }
    return $headers['Authorization'] ?? $headers['authorization'] ?? '';
}

function checkApiKey() {
    global $pdo;
    $auth = getAuthorizationHeader();
    $api_key = str_replace("Bearer ", "", $auth);
    if (!$api_key) $api_key = $_GET["api_key"] ?? "";
    
    if (empty($api_key)) {
        http_response_code(401);
        echo json_encode(["error" => "API Key is required"]);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT id, role FROM users WHERE api_key = ?");
    $stmt->execute([$api_key]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid API Key"]);
        exit;
    }
    return $user;
}
?>