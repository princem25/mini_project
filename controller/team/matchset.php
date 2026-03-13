<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/team.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $teamModel = new Team($conn);
    $team = $teamModel->teamWithTour();

    if ($team) {
        echo json_encode(["status" => "success", "message" => "teams fetched", "data" => $team]);
        exit;
    }

    echo json_encode(["status" => "failed", "message" => "data not fetched"]);
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

