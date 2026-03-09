<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$matchid = ($_POST['matchid'] ?? '');
$team1 = $_POST['team1'] ?? '';
$team2 = $_POST['team2'] ?? '';
$winner = $_POST['winner'] ?? '';
 

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/score.php";

try {

    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $matchModel = new Scores($conn);
    $match = $matchModel->matchscoreupdate($matchid, $team1,$team2,$winner);
     

    if (!$match) {
        echo json_encode(["status" => "exists", "message" => "match not exists"]);
        exit;
    }
 
        echo json_encode(["status" => "success", "message" => "matchscore updated successfully"]);
      
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
