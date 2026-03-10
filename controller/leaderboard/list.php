<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireLogin();
header('Content-Type: application/json');

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/leaderboard.php";

$tourid = $_POST['tourid'];
$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 1;
$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $matchModel = new Leaderboard($conn);
    $match = $matchModel->getLeaderboard($tourid, $limit, $offset);

    if ($match !== false) {
        echo json_encode(["status" => "success", "message" => "matches fetched", "data" => $match, "offset" => $offset]);
        exit;
    }

    echo json_encode(["status" => "failed", "message" => "Server error or query failed"]);
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
