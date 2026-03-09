<?php
require_once __DIR__ . '/../../config/auth_check.php';
requirePlayer();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/mini_pro/assets/style.css">
    <title>Player Dashboard</title>
</head>

<body>
    <div class="wrapper">
        <h2>Player Dashboard</h2>
        <p class="subtitle">Hello, <?php if(isset($_COOKIE['name'])) echo $_COOKIE['name'];?> — Welcome to Tournaments</p>

        <button id="load">Tournaments Data</button>
        <div id="data"></div>

        <p id="error"></p>

        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>

        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/tournament/load.php') ?>
    </div>
</body>

</html>