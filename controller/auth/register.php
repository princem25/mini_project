<?php
header('Content-Type: application/json');

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['pass'] ?? '');
$role     = $_POST['role'] ?? '';

if ($name === '' || $email === '' || $password === '' || $role === '') {
    echo json_encode(["status" => "error", "message" => "All fields required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format"]);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(["status" => "error", "message" => "Password must be at least 6 characters"]);
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
    $existingUser = $userModel->check($email, $role);

    if ($existingUser) {
        echo json_encode(["status" => "exists", "message" => "Email already registered"]);
        exit;
    }

    $created = $userModel->register($email, $name, $password, $role);

    if ($created) {
        echo json_encode(["status" => "registered", "message" => "Registration successful"]);
        exit;
    }

    echo json_encode(["status" => "failed", "message" => "Registration failed"]);
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . $e->getLine() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
}

