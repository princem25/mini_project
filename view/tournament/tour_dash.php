<?php
require_once('C:/xampp_new/htdocs/mini_pro/view/admin/sessionAdmin.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>welcome , <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']) ?></h2>

    <a href="../admin/admin_dash.php">Admin dashboard</a><br><br><br>
    <a href="../tournament/create.php">Create Tournament</a><br><br>
    <a href="">Edit Tournament</a><br><br>
    <a href="">Delete Tournament</a><br><br>
    <a href="">change status of Tournament</a><br><br>
    <p id="error"></p>
    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>

</body>

</html>