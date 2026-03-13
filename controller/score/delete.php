<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$matchid = trim($_POST['matchid'] ?? '');

if ($matchid === '') {
    echo json_encode(["status" => "error", "message" => "Match ID is required"]);
    exit;
}

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/score.php";
require_once __DIR__ . "/../../model/match.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $scoreModel = new Scores($conn);
    $matchesModel = new Matches($conn);

    // Check if match exists and is scheduled (not completed)
    $matchDetails = $matchesModel->getMatch($matchid);
    if (!$matchDetails) {
        echo json_encode(["status" => "error", "message" => "Match not found"]);
        exit;
    }

    if ($matchDetails['status'] === 'Completed') {
        echo json_encode(["status" => "error", "message" => "Cannot delete score for completed match"]);
        exit;
    }

    // Check if score exists
    $scoreExists = $scoreModel->checkmatchscore($matchid);
    if (!$scoreExists) {
        echo json_encode(["status" => "error", "message" => "Score not found for this match"]);
        exit;
    }

    // Delete the score
    $deleted = $scoreModel->deletescore($matchid);
    if ($deleted) {
        echo json_encode(["status" => "success", "message" => "Score deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete score"]);
    }

} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
