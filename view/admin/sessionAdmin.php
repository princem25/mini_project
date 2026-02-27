<?php
if(session_status() !== PHP_SESSION_ACTIVE){
session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location:/mini_pro/view/auth/login.php");
    exit;
}
?>