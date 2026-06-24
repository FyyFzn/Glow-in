<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$user_id = $_GET["id"] ?? null;

try {
    if ($method === "GET") {
        if ($user_id) {
            // Get single user
            $stmt = $pdo->prepare("SELECT id, username, name, bio, location, profile_pic, header_pic, created_at FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Get all users
            $stmt = $pdo->query("SELECT id, username, name, bio, location, profile_pic, header_pic, created_at FROM users ORDER BY username ASC");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($data);
    } 
    elseif ($method === "PUT") {
        // Update user profile
        if (!$user_id || $user_id != $user['id']) {
            throw new Exception("Unauthorized");
        }
        
        $input = json_decode(file_get_contents("php://input"), true);
        
        $allowed_fields = ['name', 'bio', 'location', 'profile_pic', 'header_pic'];
        $update_data = [];
        
        foreach ($allowed_fields as $field) {
            if (isset($input[$field])) {
                $update_data[$field] = $input[$field];
            }
        }
        
        if (empty($update_data)) {
            throw new Exception("No data to update");
        }
        
        $set_clause = implode(", ", array_map(fn($k) => "$k = ?", array_keys($update_data)));
        $values = array_values($update_data);
        $values[] = $user_id;
        
        $stmt = $pdo->prepare("UPDATE users SET $set_clause WHERE id = ?");
        $stmt->execute($values);
        
        echo json_encode(["success" => true, "message" => "Profile updated successfully"]);
    }
    else {
        throw new Exception("Method not allowed");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>