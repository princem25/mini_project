<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();

require_once __DIR__ . '/../../config/dbconfig.php';
$stmtTours = $conn->query("SELECT COUNT(*) as c FROM tournaments");
$tours = $stmtTours->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

$stmtTeams = $conn->query("SELECT COUNT(*) as c FROM teams");
$teams = $stmtTeams->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

$stmtPlayers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role_id = 2");
$players = $stmtPlayers->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

$stmtMatches = $conn->query("SELECT COUNT(*) as c FROM matches");
$matches = $stmtMatches->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/mini_project/assets/style.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="wrapper">
  
        <h2>Admin Dashboard</h2>
        <h3>Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></h3>
        <p class="subtitle">You have admin access</p>

        <div class="summary-cards" style="display: flex; gap: 10px; margin-bottom: 20px; text-align: center;">
            <div style="flex:1; background:#222; padding: 15px; border-radius: 8px;">
                <h3 style="color:#f39c12; margin:0; font-size: 24px;"><?= $tours ?></h3>
                <p style="margin: 5px 0 0; font-size: 14px; color:#aaa;">Tournaments</p>
            </div>
            <div style="flex:1; background:#222; padding: 15px; border-radius: 8px;">
                <h3 style="color:#3498db; margin:0; font-size: 24px;"><?= $teams ?></h3>
                <p style="margin: 5px 0 0; font-size: 14px; color:#aaa;">Teams</p>
            </div>
            <div style="flex:1; background:#222; padding: 15px; border-radius: 8px;">
                <h3 style="color:#2ecc71; margin:0; font-size: 24px;"><?= $players ?></h3>
                <p style="margin: 5px 0 0; font-size: 14px; color:#aaa;">Players</p>
            </div>
            <div style="flex:1; background:#222; padding: 15px; border-radius: 8px;">
                <h3 style="color:#e74c3c; margin:0; font-size: 24px;"><?= $matches ?></h3>
                <p style="margin: 5px 0 0; font-size: 14px; color:#aaa;">Matches</p>
            </div>
        </div>

        <div class="nav-links">
            <a href="../tournament/dashboard.php">Tournament Management</a>
            <a href="../team/dashboard.php">Team Management</a>
            <a href="../player/dashboard.php">Player Management</a>
            <a href="../match/dashboard.php">Match Management</a>
            <a href="../match_score/dashboard.php">Match score</a>
            <a href="../leaderboard/dashboard.php">Match Leaderboard</a>
        </div>

        <p id="error"></p>
        <?php require_once('C:/xampp_3/htdocs/mini_project/view/auth/logout.php') ?>
    </div>
</body>

</html>
