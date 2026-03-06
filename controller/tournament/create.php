<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$tourName = trim($_POST['name'] ?? '');
$start = trim($_POST['start'] ?? '');
$end = $_POST['end'] ?? '';
$type = $_POST['type'] ?? '';
$status = $_POST['status'] ?? '';

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/tournament.php";

try {
    if ($start <= $end) {
        if ($start <= date("Y-m-d") && $end >= date("Y-m-d")) {
            $status = "Ongoing";
        } else if ($start <= date("Y-m-d") && $end <= date("Y-m-d")) {
            $status = "Completed";
        }

        if ($connected != 1) {
            echo json_encode(["status" => "failed", "message" => "Database connection failed"]);
            exit;
        }

        $tourModel = new Tournament($conn);
        $tour = $tourModel->checkTour($tourName);

        if ($tour) {
            echo json_encode(["status" => "exists", "message" => "tournament already exists"]);
            exit;
        }

        $newTour = $tourModel->registerTour($tourName, $start, $end, $type, $status);

        if ($newTour) {
            echo json_encode(["status" => "success", "message" => "tournament created successfully"]);
            exit;
        } else {
            echo json_encode(["status" => "failed", "message" => "Invalid details"]);
        }
    } else {
        echo json_encode(["status" => "failed", "message" => "invalid dates"]);
    }
} catch (PDOException $e) {
    file_put_contents(__DIR__ . "/../../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "error", "message" => "Server error"]);
} finally {
    $conn = null;
}
