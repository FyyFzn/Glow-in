<?php
require_once "middleware.php";
$user = checkApiKey();
$method = $_SERVER["REQUEST_METHOD"];
$id = $_GET["id"] ?? null;

try {
    if ($method === "GET") {
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query("SELECT * FROM categories");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($data);
    } 
    elseif ($method === "POST") {
        $input = json_decode(file_get_contents("php://input"), true) ?: $_POST;
        $keys = array_keys($input);
        $fields = implode(", ", $keys);
        $placeholders = implode(", ", array_fill(0, count($keys), "?"));
        $stmt = $pdo->prepare("INSERT INTO categories ($fields) VALUES ($placeholders)");
        $stmt->execute(array_values($input));
        echo json_encode(["success" => true, "message" => "Data created", "id" => $pdo->lastInsertId()]);
    }
    elseif ($method === "PUT") {
        if (!$id) throw new Exception("ID required for PUT");
        $input = json_decode(file_get_contents("php://input"), true);
        $sets = [];
        $values = [];
        foreach ($input as $key => $val) {
            $sets[] = "$key = ?";
            $values[] = $val;
        }
        $values[] = $id;
        $stmt = $pdo->prepare("UPDATE categories SET " . implode(", ", $sets) . " WHERE id = ?");
        $stmt->execute($values);
        echo json_encode(["success" => true, "message" => "Data updated"]);
    }
    elseif ($method === "DELETE") {
        if (!$id) throw new Exception("ID required for DELETE");
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Data deleted"]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>