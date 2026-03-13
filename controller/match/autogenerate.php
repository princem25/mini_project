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
require_once __DIR__ . "/../../model/match.php";

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

    $teams = [];
    foreach ($teamsResult as $t) {
        $teams[] = $t['team_id'];
    }

    // Calculate scheduling limits based on tournament duration
    $startDate = new DateTime($tour['start_date']);
    $endDate = new DateTime($tour['end_date']);
    $availableDays = (int) $startDate->diff($endDate)->format('%a') + 1;

    $numTeams = count($teams);
    $requiredDays = ($numTeams * ($numTeams - 1)) / 2; // Each match on a separate day
    $totalMatches = ($numTeams * ($numTeams - 1)) / 2;

    // Determine maximum teams that fit into the available rounds (1 match per day)
    $maxTeams = 0;
    for ($n = 2; $n <= 100; $n++) { // Reasonable upper limit
        $needed = ($n * ($n - 1)) / 2;
        if ($needed <= $availableDays) {
            $maxTeams = $n;
        } else {
            break;
        }
    }

    if ($requiredDays > $availableDays) {
        echo json_encode([
            "status" => "error",
            "message" => "Tournament length is {$availableDays} days but scheduling {$numTeams} teams requires {$requiredDays} days ({$totalMatches} matches, 1 per day).",
            "suggestion" => "Either extend the tournament end date or reduce teams to {$maxTeams} or fewer for this date range.",
            "available_days" => $availableDays,
            "required_days" => $requiredDays,
            "max_teams" => $maxTeams,
            "teams" => $numTeams,
            "total_matches" => $totalMatches,
        ]);
        exit;
    }

    // Generate all unique match pairs (1 match per day), ensuring canonical order (smaller team_id first)
    $matchPairs = [];
    for ($i = 0; $i < $numTeams; $i++) {
        for ($j = $i + 1; $j < $numTeams; $j++) {
            $teamA = min($teams[$i], $teams[$j]);
            $teamB = max($teams[$i], $teams[$j]);
            $matchPairs[] = [$teamA, $teamB];
        }
    }

    $matchModel = new Matches($conn);
    $matchesGenerated = 0;
    $currentDate = clone $startDate;

    foreach ($matchPairs as $pair) {
        $team1 = $pair[0];
        $team2 = $pair[1];
        $dateStr = $currentDate->format('Y-m-d H:i:s');

        // Check if match already exists
        $exists = $matchModel->checkMatch($team1, $team2);
        if (!$exists) {
            $matchModel->registerMatch($tourid, $team1, $team2, $dateStr, "Upcoming");
            $matchesGenerated++;
        }

        // Move to next day
        $currentDate->modify('+1 day');
    }


    echo json_encode([
        "status" => "success",
        "message" => "$matchesGenerated matches generated successfully!",
        "available_days" => $availableDays,
        "required_days" => $requiredDays,
        "max_teams" => $maxTeams,
        "teams" => $rawTeamCount,
        "total_matches" => $totalMatches,
        "matches_generated" => $matchesGenerated
    ]);

} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

