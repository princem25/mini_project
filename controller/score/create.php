<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$matchid = ($_POST['matchid'] ?? '');
$team1 = (int)($_POST['team1'] ?? 0);
$team2 = (int)($_POST['team2'] ?? 0);
$team1id = $_POST['team1id'] ?? '';
$team2id = $_POST['team2id'] ?? '';

if ($team1 < 0 || $team2 < 0) {
    echo json_encode(["status" => "failed", "message" => "Negative scores are not allowed"]);
    exit;
}

if ($team1 > $team2) {
    $winner = $team1id;
} else if ($team2 > $team1) {
    $winner = $team2id;
} else {
    $winner = null;
}

$status = 'Completed';


require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/score.php";

try {

    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $matchModel = new Scores($conn);
    $matchexists = $matchModel->checkmatchscore($matchid);

    if ($matchexists) {
        echo json_encode(["status" => "exists", "message" => "matchscore already updated"]);
        exit;
    }

    $match = $matchModel->matchscoreReg($matchid, $team1, $team2, $winner, $status);


    if (!$match) {
        echo json_encode(["status" => "exists", "message" => "match not exists"]);
        exit;
    }

    require_once __DIR__ . "/../../model/match.php";
    require_once __DIR__ . "/../../model/tournament.php";
    $matchesModel = new Matches($conn);
    $tourModel = new Tournament($conn);
    
    $matchDetails = $matchesModel->getMatch($matchid);
    if ($matchDetails && isset($matchDetails['tour_id'])) {
        $tourModel->checkAndCompleteTournament($matchDetails['tour_id']);
    }

    echo json_encode(["status" => "success", "message" => "matchscore created successfully"]);
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

