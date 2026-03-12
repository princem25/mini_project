<?php
require_once __DIR__ . "/../../config/auth_check.php";
requireAdmin();
header('Content-Type: application/json');

$id = trim($_POST['id'] ?? '');
$tourName = preg_replace('/\s+/', ' ', strtolower(trim($_POST['name'] ?? '')));
$start = trim($_POST['start'] ?? '');
$end = $_POST['end'] ?? '';
$type = $_POST['type'] ?? '';
$status = $_POST['status'] ?? '';

if ($id === '' || $tourName === '') {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

if (!preg_match("/^[a-zA-Z0-9 ]+$/", $tourName)) {
    echo json_encode(["status" => "error", "message" => "Special characters are not allowed in name"]);
    exit;
}

if ($end < $start) {
    echo json_encode(["status" => "error", "message" => "End date must be the same as or after the start date."]);
    exit;
}

require_once __DIR__ . "/../../config/dbconfig.php";
require_once __DIR__ . "/../../model/tournament.php";

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

    $newTour = $tourModel->updateTour($id, $tourName, $start, $end, $type, $status);

    if ($newTour !== false) {
        echo json_encode(["status" => "success", "message" => "tournament updated successfully"]);
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
