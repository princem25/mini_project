<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function requireAdmin()
{
    if (!isset($_SESSION['role']) || (int)$_SESSION['role'] !== 1) {
       header('location: /mini_pro/view/auth/login.php');
        exit;
    }
}

function requireLogin()
{
    if (!isset($_SESSION['role'])) {
        header('location: /mini_pro/view/auth/login.php');
        exit;
    }
}

function requirePlayer()
{
    if (!isset($_SESSION['role']) || (int)$_SESSION['role'] !== 2) {
        header('location: /mini_pro/view/auth/login.php');
        exit;
    }
}
