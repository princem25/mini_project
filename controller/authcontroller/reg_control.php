<?php
header('Content-Type: application/json');

$username = trim($_POST['name'] ?? '');
$password = trim($_POST['pass'] ?? '');
$role     = $_POST['role'] ?? '';

if ($username === '' || $password === '' || $role === '') {
    echo json_encode([
        "status" => "error",
        "message" => "All fields required"
    ]);
    exit;
}

require_once __DIR__ . "/../config/dbconfig.php";
require_once __DIR__ . "/../model/usermodel.php";

try {

    if ($connected != 1) {
        echo json_encode([
            "status" => "failed",
            "message" => "Database connection failed"
        ]);
        exit;
    }

    $userModel = new User($conn);


    $existingUser = $userModel->check($username, $role);

    if ($existingUser) {
        echo json_encode([
            "status" => "exists",
            "message" => "Username already registered"
        ]);
        exit;
    }

    // ✅ register user
    $created = $userModel->register($username, $password, $role);

    if ($created) {
        echo json_encode([
            "status" => "registered",
            "message" => "Registration successful"
        ]);
        exit;
    }

    echo json_encode([
        "status" => "failed",
        "message" => "Registration failed"
    ]);
} catch (PDOException $e) {

    file_put_contents(
        __DIR__ . "/../error.txt",
        date("H:i:s Y-m-d : ") . $e->getMessage() . $e->getLine() . PHP_EOL,
        FILE_APPEND
    );

    echo json_encode([
        "status" => "error",
        "message" => "Server error"
    ]);
}
