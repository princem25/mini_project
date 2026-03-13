<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$teamid = trim($_POST['teamid'] ?? '');
$tourid = trim($_POST['tourid'] ?? '');

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/team.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $teamModel = new Team($conn);

    require_once __DIR__ . "/../../model/player.php";
    $playerModel = new Player($conn);
    $playerCount = $playerModel->getPlayerCountByTeam($teamid);
    
    if ($playerCount < 5) {
        echo json_encode(["status" => "error", "message" => "A team must have minimum 5 players to participate in a tournament."]);
        exit;
    }

    // Verify team is not currently tied up in an ongoing tournament
    $teamInfo = $teamModel->getTeamById($teamid);
    if ($teamInfo && $teamInfo['tour_id'] != null) {
        $currentTourId = $teamInfo['tour_id'];
        
        require_once __DIR__ . "/../../model/tournament.php";
        $tourModel = new Tournament($conn);
        $tourInfo = $tourModel->getTourById($currentTourId);
        
        // Prevent reassignment if the current tournament is active or ongoing
        if ($tourInfo && strtolower($tourInfo['status']) !== 'completed') {
            echo json_encode(["status" => "error", "message" => "Team is currently assigned to an active tournament."]);
            exit;
        }
    }

    $team = $teamModel->teamAssignTour($teamid, $tourid);

    if (!$team) {
        echo json_encode(["status" => "failed", "message" => "team not updated"]);
        exit;
    } else {
        echo json_encode(["status" => "success", "message" => "team updated"]);
    }
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

