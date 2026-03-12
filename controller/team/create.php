<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$teamname = preg_replace('/\s+/', ' ', strtolower(trim($_POST['name'] ?? '')));

if ($teamname === '') {
    echo json_encode(["status" => "error", "message" => "Team name is required"]);
    exit;
}

if (!preg_match("/^[a-zA-Z0-9 ]+$/", $teamname)) {
    echo json_encode(["status" => "error", "message" => "Special characters are not allowed in name"]);
    exit;
}

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/team.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $teamModel = new Team($conn);
    $team = $teamModel->checkTeam($teamname);

    if ($team) {
        echo json_encode(["status" => "exists", "message" => "team already exists"]);
        exit;
    }

    $newTeam = $teamModel->registerTeam($teamname);

    if ($newTeam) {
        echo json_encode(["status" => "success", "message" => "team created successfully"]);
        exit;
    } else {
        echo json_encode(["status" => "failed", "message" => "Invalid details"]);
    }
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
