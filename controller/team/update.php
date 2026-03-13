<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$id = trim($_POST['id'] ?? '');
$name = preg_replace('/\s+/', ' ', strtolower(trim($_POST['name'] ?? '')));

if ($id === '' || $name === '') {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

if (!preg_match("/^[a-zA-Z0-9 ]+$/", $name)) {
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
    $team = $teamModel->checkTeamid($id);

    if (!$team) {
        echo json_encode(["status" => "failed", "message" => "team not exists"]);
        exit;
    }

    $newTeam = $teamModel->updateTeam($id, $name);

    if ($newTeam !== false) {
        echo json_encode(["status" => "success", "message" => "team updated successfully"]);
        exit;
    } else {
        echo json_encode(["status" => "failed", "message" => "Invalid details not updated"]);
    }
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

