<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$id = trim($_POST['id'] ?? '');
$status = trim($_POST['status'] ?? '');

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/tournament.php";
require_once __DIR__ . "/../../model/match.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $tourModel = new Tournament($conn);
    $tour = $tourModel->checkTourid($id);

    if (!$tour) {
        echo json_encode(["status" => "failed", "message" => "tournament not exists"]);
        exit;
    }

    // Only allow status changes for verified tournaments
    if (empty($tour['verified']) || $tour['verified'] != 1) {
        echo json_encode(["status" => "failed", "message" => "Cannot change status for unverified tournament"]);
        exit;
    }

    // Normalise status for case-insensitive checks
    $normalizedStatus = strtolower(trim($status));

    // If trying to set status to 'ongoing', ensure tournament start datetime has arrived
    if ($normalizedStatus === 'ongoing') {
        $now = new DateTime('now');
        $startDateTime = new DateTime($tour['start_date']);

        if ($now < $startDateTime) {
            echo json_encode(["status" => "failed", "message" => "Cannot set tournament to ongoing before its start date ({$startDateTime->format('Y-m-d H:i:s')})"]);
            exit;
        }

        // Do not allow flipping completed back to ongoing
        if (strtolower($tour['status']) === 'completed') {
            echo json_encode(["status" => "failed", "message" => "Cannot set a completed tournament back to ongoing"]);
            exit;
        }
    }

    // If trying to set status to 'completed', ensure all matches are completed
    if ($normalizedStatus === 'completed') {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM matches WHERE tour_id = ?");
        $stmt->execute([$id]);
        $totalMatches = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        if ($totalMatches > 0) {
            $stmt2 = $conn->prepare("SELECT COUNT(*) as incomplete FROM matches WHERE tour_id = ? AND status != 'Completed'");
            $stmt2->execute([$id]);
            $incomplete = $stmt2->fetch(PDO::FETCH_ASSOC)['incomplete'] ?? 0;

            if ($incomplete > 0) {
                echo json_encode(["status" => "failed", "message" => "Cannot complete tournament: $incomplete match(es) not completed"]);
                exit;
            }
        }
    }

    // Use normalized status for update (so 'completed' and 'Completed' behave the same)
    $status = ucfirst($normalizedStatus);

    $newTour = $tourModel->statuschange($id, $status);

    if ($newTour) {
        echo json_encode(["status" => "success", "message" => "tournament status updated successfully"]);
        exit;
    } else {
        echo json_encode(["status" => "failed", "message" => "Invalid details not updated"]);
    }
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}

