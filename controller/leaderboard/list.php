<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireLogin();
header('Content-Type: application/json');

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/leaderboard.php";

require_once __DIR__ . "/../../model/tournament.php";

$tourid = $_POST['tourid'];
$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 1;
$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $tourModel = new Tournament($conn);
    $tourInfo = $tourModel->getTourById($tourid);
    $tourStatus = $tourInfo ? strtolower($tourInfo['status']) : "active";
    $tourType   = $tourInfo ? strtolower($tourInfo['type']) : "league";

    $matchModel = new Leaderboard($conn);
    $match = $matchModel->getLeaderboard($tourid, $limit, $offset);
    $knockoutChampion = null;

    if ($tourStatus === "completed" && $tourType === "knockout") {
        $champData = $matchModel->getKnockoutChampion($tourid);
        if ($champData) {
            $knockoutChampion = [
                'team_id' => $champData['team_id'],
                'team_name' => $champData['team_name']
            ];
        }
    }

    if ($match !== false) {
        echo json_encode([
            "status" => "success", 
            "message" => "matches fetched", 
            "data" => $match, 
            "offset" => $offset, 
            "tour_status" => $tourStatus, 
            "tour_type" => $tourType,
            "knockout_champion" => $knockoutChampion
        ]);
        exit;
    }

    echo json_encode(["status" => "failed", "message" => "Server error or query failed"]);
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

