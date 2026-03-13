<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$matchid = trim($_POST['matchid'] ?? '');
$status = $_POST['status'] ?? '';

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/match.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $matchModel = new Matches($conn);
    $match = $matchModel->updatematch($matchid,$status);

    if (!$match) {
        echo json_encode(["status" => "failed", "message" => "match not exists not updated"]);
        exit;
    }

    // If this update marks a match as completed, check if tournament should be marked completed
    if (strtolower($status) === 'completed') {
        require_once __DIR__ . "/../../model/tournament.php";
        $tourModel = new Tournament($conn);
        $matchDetails = $matchModel->getMatch($matchid);
        if ($matchDetails && isset($matchDetails['tour_id'])) {
            $tourModel->checkAndCompleteTournament($matchDetails['tour_id']);
        }
    }

    echo json_encode(["status" => "success", "message" => "match updated successully"]);
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

