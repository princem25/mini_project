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

        <h2>Match Leaderboard</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>
        <label>Tournament</label>
        <select id="tourselect">
            <option value="">-- Select Tournament --</option>
        </select><br><br>
        <button id="load">Load leaderboard</button>
        <div id="data"></div>
        <br>

        <p id="success"></p>
        <p id="error"></p>

        
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/leaderboard/list.php') ?>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/tournament/verifiedtour.php') ?>

    </div>
</body>

</html>