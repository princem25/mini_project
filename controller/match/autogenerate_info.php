<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$tourid = trim($_POST['tourid'] ?? '');

if ($tourid === '') {
    echo json_encode(["status" => "error", "message" => "Tournament ID is required"]);
    exit;
}

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/tournament.php";
require_once __DIR__ . "/../../model/team.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $tourModel = new Tournament($conn);
    $tour = $tourModel->getTourById($tourid);

    if (!$tour || strtolower($tour['type']) !== 'league') {
        echo json_encode(["status" => "error", "message" => "Invalid tournament or not a league tournament"]);
        exit;
    }

    $teamModel = new Team($conn);
    $teamsResult = $teamModel->getTeamsByTourId($tourid);
    $rawTeamCount = count($teamsResult);

    if ($rawTeamCount < 2) {
        echo json_encode(["status" => "error", "message" => "Not enough teams to generate matches", "teams" => $rawTeamCount]);
        exit;
    }

    $startDate = new DateTime($tour['start_date']);
    $endDate = new DateTime($tour['end_date']);
    $availableDays = (int) $startDate->diff($endDate)->format('%a') + 1;

    $numTeams = $rawTeamCount;
    $requiredDays = ($numTeams * ($numTeams - 1)) / 2; // Each match on a separate day
    $totalMatches = ($numTeams * ($numTeams - 1)) / 2;

    $maxTeams = 0;
    for ($n = 2; $n <= 100; $n++) { // Reasonable upper limit
        $needed = ($n * ($n - 1)) / 2;
        if ($needed <= $availableDays) {
            $maxTeams = $n;
        } else {
            break;
        }
    }

    echo json_encode([
        "status" => "success",
        "message" => "Schedule info fetched",
        "available_days" => $availableDays,
        "required_days" => $requiredDays,
        "max_teams" => $maxTeams,
        "teams" => $numTeams,
        "total_matches" => $totalMatches,
        "can_generate" => $requiredDays <= $availableDays,
    ]);

} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
