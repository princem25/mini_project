  <?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$id = trim($_POST['id'] ?? '');

if ($id === '') {
    echo json_encode(["status" => "error", "message" => "Match ID is required"]);
    exit;
}

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/match.php";
require_once __DIR__ . "/../../model/score.php";

try {
    if ($connected != 1) {
        echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
        exit;
    }

    $matchModel = new Matches($conn);
    $scoreModel = new Scores($conn);

    // Check if match exists
    $match = $matchModel->getMatch($id);
    if (!$match) {
        echo json_encode(["status" => "error", "message" => "Match not found"]);
        exit;
    }

    // Only allow deletion if match is not completed
    if ($match['status'] === 'Completed') {
        echo json_encode(["status" => "error", "message" => "Cannot delete completed match"]);
        exit;
    }

    // Delete associated score if exists
    $scoreModel->deletescore($id);

    // Delete the match
    $deleted = $matchModel->deleteMatch($id);
    if ($deleted) {
        echo json_encode(["status" => "success", "message" => "Match deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete match"]);
    }

} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
?>

