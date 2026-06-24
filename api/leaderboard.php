<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "GET") {
        $limit = isset($_GET["limit"]) ? (int)$_GET["limit"] : null;
        
        $sql = "SELECT id, username, name, profile_pic, points 
                FROM users 
                ORDER BY points DESC, created_at ASC";
        
        if ($limit) {
            $sql .= " LIMIT " . $limit;
        }
        
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Add default profile pic using AI-generated human photos if empty
        $aiProfilePics = [
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=200&q=80',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80'
        ];
        foreach ($data as &$userData) {
            if (empty($userData['profile_pic'])) {
                $picIndex = crc32($userData['username']) % count($aiProfilePics);
                $userData['profile_pic'] = $aiProfilePics[$picIndex];
            }
        }
        
        echo json_encode($data);
    } else {
        throw new Exception("Method not allowed");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
