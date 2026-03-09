<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/mini_pro/assets/style.css">
    <title>Match Dashboard</title>
</head>

<body>
    <div class="wrapper">
        <div class="breadcrumb">
            <a href="../admin/dashboard.php">Admin Dashboard</a>
        </div>

        <h2>Match Management</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="load">Load Tournaments</button>
        <div id="data"></div>
        <br>
        <button id="loadteams">View Teams</button>
        <div id="datateam"></div>
        <br>
        <button id="loadplayer">View Players</button>
        <div id="dataplayer"></div>

        <div class="nav-links">
            <a href="../match/create.php">Create Match</a>
        
            
        </div>

        <p id="error"></p>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/tournament/load.php') ?>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/team/load.php') ?>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/player/load.php') ?>
    </div>
</body>

</html>