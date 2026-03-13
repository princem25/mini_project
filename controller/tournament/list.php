<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireLogin();
header('Content-Type: application/json');

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/tournament.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : null;

    $tourModel = new Tournament($conn);
    $tour = $tourModel->getTour($limit, $offset);

    if ($tour) {
        echo json_encode(["status" => "success", "message" => "tournament fetched", "data" => $tour]);
        exit;
    }

    echo json_encode(["status" => "failed", "message" => "data not fetched"]);
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

