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
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="wrapper">
        <h2>Admin Dashboard</h2>
        <h3>Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></h3>
        <p class="subtitle">You have admin access</p>

        <div class="nav-links">
            <a href="../tournament/dashboard.php">Tournament Management</a>
            <a href="../team/dashboard.php">Team Management</a>
            <a href="../player/dashboard.php">Player Management</a>
            <a href="../match/dashboard.php">Match Management</a>
            <a href="../match_score/dashboard.php">Match score</a>
            <a href="../leaderboard/dashboard.php">Match Leaderboard</a>
        </div>

        <p id="error"></p>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>
    </div>
</body>

</html>