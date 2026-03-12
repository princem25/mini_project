<?php
header('Content-Type: application/json');

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['pass'] ?? '');
$role     = (int)($_POST['role'] ?? 0);

if ($email === '' || $password === '' || $role === 0) {
    echo json_encode(["status" => "error", "message" => "All fields required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format"]);
    exit;
}

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/usermodel.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $userModel = new User($conn);
    $user = $userModel->login($email, $password, $role);



    if ($user) {
        session_start();
        session_regenerate_id(true);
        $_SESSION['role'] = (int)$role;
        setcookie("name", $user['name'], time() + (86400 * 30), "/");

        echo json_encode(["status" => "success", "role" => $_SESSION['role']]);
        exit;
    } else {
        echo json_encode(["status" => "failed", "message" => "Invalid credentials or register "]);
    }
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
