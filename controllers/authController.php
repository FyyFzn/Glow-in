<?php
session_start();
require_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
if ($input && is_array($input)) {
    $_POST = array_merge($_POST, $input);
}
$isJson = !empty($input) || (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'] ?: $user['username'];
            $_SESSION['profile_pic'] = $user['profile_pic'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($user['username']) . '&background=ff6b00&color=ffffff';
            $_SESSION['role'] = $user['role'];
            $_SESSION['api_key'] = $user['api_key'];

            $redirect = "../pages/home.php";
            if ($user['role'] === 'admin') {
                $redirect = "../admin/index.php";
            } elseif ($user['role'] === 'spesialis') {
                $redirect = "../spesialis/index.php";
            }

            if ($isJson) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'redirect' => $redirect]);
                exit();
            } else {
                header("Location: " . $redirect);
                exit();
            }
        } else {
            if ($isJson) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Username atau Password salah!']);
                exit();
            } else {
                echo "<script>alert('Username atau Password salah!'); window.location.href='../pages/login.php';</script>";
                exit();
            }
        }
    } elseif ($action === 'register') {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm_password) {
            if ($isJson) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Password tidak sama!']);
                exit();
            } else {
                echo "<script>alert('Password tidak sama!'); window.location.href='../pages/register.php';</script>";
                exit();
            }
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            if ($isJson) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Username sudah terpakai!']);
                exit();
            } else {
                echo "<script>alert('Username sudah terpakai!'); window.location.href='../pages/register.php';</script>";
                exit();
            }
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $api_key = bin2hex(random_bytes(16));

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, api_key, role) VALUES (?, ?, ?, 'postinger')");
            if ($stmt->execute([$username, $hashed_password, $api_key])) {
                if ($isJson) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'redirect' => '../pages/login.php?registered=1']);
                    exit();
                } else {
                    header("Location: ../pages/register.php?success=1");
                    exit();
                }
            } else {
                $error = $stmt->errorInfo();
                if ($isJson) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => "Gagal mendaftar: " . $error[2]]);
                    exit();
                } else {
                    echo "<script>alert('Gagal mendaftar: " . addslashes($error[2]) . "'); window.location.href='../pages/register.php';</script>";
                    exit();
                }
            }
        } catch (PDOException $e) {
            if ($isJson) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => "Database Error: " . $e->getMessage()]);
                exit();
            } else {
                echo "<script>alert('Database Error: " . addslashes($e->getMessage()) . "'); window.location.href='../pages/register.php';</script>";
                exit();
            }
        }
    }
} elseif ($method === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'logout') {
        session_destroy();
        header("Location: ../pages/login.php");
        exit();
    }
}
?>
