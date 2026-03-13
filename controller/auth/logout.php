<?php
session_start();
session_unset();
setcookie("name", "", time() - 3600, "/");
session_destroy();

header('Content-Type: application/json');
if (session_status() === PHP_SESSION_ACTIVE) {
    echo json_encode(["status" => "failed", "message" => "something is wrong try again"]);
} else {
    echo json_encode(["status" => "ok", "message" => "session destroyed"]);
}

