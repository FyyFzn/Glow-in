<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];

try {
    if ($method === "GET") {
        $limit = isset($_GET["limit"]) ? (int)$_GET["limit"] : null;

        $action = $_GET["action"] ?? "";

        $sql = "SELECT * FROM (
                    SELECT users.id, users.username, users.name, users.profile_pic, users.created_at,
                    (
                        (SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id AND posts.is_anonymous = FALSE) * 10 +
                        (SELECT COUNT(*) FROM comments WHERE comments.user_id = users.id) * 5 +
                        (SELECT COUNT(*) FROM likes JOIN posts ON likes.post_id = posts.id WHERE posts.user_id = users.id) * 2
                    ) AS points 
                    FROM users 
                ) AS user_points
                WHERE points > 0
                ORDER BY points DESC, created_at ASC";

        if ($action === "my_rank") {
            $stmt = $pdo->query($sql);
            $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $myRank = "-";
            $myPoints = 0;
            foreach ($allUsers as $idx => $u) {
                if ($u['id'] == $user['id']) {
                    $myRank = $idx + 1;
                    $myPoints = $u['points'];
                    break;
                }
            }
            echo json_encode(["rank" => $myRank, "points" => $myPoints]);
            exit;
        }

        if ($limit) {
            $sql .= " LIMIT " . $limit;
        }

        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as &$userData) {
            if (empty($userData['profile_pic'])) {
                $displayName = !empty($userData['name']) ? $userData['name'] : $userData['username'];
                $userData['profile_pic'] = 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=ff6b00&color=ffffff';
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
