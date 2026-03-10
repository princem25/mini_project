<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$matchid = ($_POST['matchid'] ?? '');
$team1 = $_POST['team1'] ?? '';
$team2 = $_POST['team2'] ?? '';
$winner = $_POST['winner'] ?? '';
$status = $_POST['status'] ?? '';
$team1id = $_POST['team1id'] ?? '';
$team2id = $_POST['team2id'] ?? '';

if ($team1 === $team2 && $winner != "Draw") {
    echo json_encode(["status" => "failed", "message" => "winner should be draw"]);
    exit;
} else if ($team1 > $team2 && $winner != $team1id) {
    echo json_encode(["status" => "failed", "message" => "winner should be team 1"]);
    exit;
} else if ($team1 < $team2 && $winner != $team2id) {
    echo json_encode(["status" => "failed", "message" => "winner should be team 2"]);
    exit;
}


if($winner == "Draw") $winner = null;



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

    echo json_encode(["status" => "success", "message" => "matchscore created successfully"]);
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
