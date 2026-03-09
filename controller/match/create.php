<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$tourid = trim($_POST['tourid'] ?? '');
$team1 = $_POST['team1'] ?? '';
$team2 = $_POST['team2'] ?? '';
$date = $_POST['date'] ?? '';
$status = $_POST['status'] ?? '';

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/match.php";

try {

    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $matchModel = new Matches($conn);
    $match = $matchModel->checkMatch($team1, $team2);
    $checktour = $matchModel->checkTour($tourid, $team1, $team2);

    if (!$checktour) {
        echo json_encode(["status" => "failed", "message" => "both team have diff tournaments"]);
        exit;
    }

    $checkdate = $matchModel->checkDate($tourid, $date);

    if (!$checkdate) {
        echo json_encode(["status" => "failed", "message" => "invalid dates not exists between tournament"]);
        exit;
    }

    if ($match) {
        echo json_encode(["status" => "exists", "message" => "match already exists"]);
        exit;
    }

    $newmatch = $matchModel->registerMatch($tourid, $team1, $team2, $date,$status);

    if ($newmatch) {
        echo json_encode(["status" => "success", "message" => "match created successfully"]);
        exit;
    } else {
        echo json_encode(["status" => "failed", "message" => "Invalid details"]);
    }
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
