<?php
require_once('C:/xampp_new/htdocs/mini_pro/view/admin/sessionAdmin.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Document</title>
</head>

<body>
    <h2>welcome , <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']) ?></h2>

    <a href="../admin/admin_dash.php">Admin dashboard</a><br><br>
    <button id="load">Load Tournaments</button><br><br>
    <div id="data"></div><br>
    <button id="loadteams">view Teams</button><br><br>
  <div id="datateam"></div><br>
      <button id="loadplayer">view Player</button><br><br>
  <div id="dataplayer"></div><br>
    <a href="../player/addteam.php">add Player to Team</a><br><br>
    <a href="../player/editplayer.php">Edit Player</a><br><br>
    <a href="../player/remove_team.php">remove player from team</a><br><br>
    
    <p id="error"></p>
    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>
    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/tournament/load_data.php') ?>
    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/team/loaddata.php') ?>
    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/player/playerdata.php') ?>

     
</body>

</html>