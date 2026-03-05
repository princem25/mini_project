<?php
session_start();

if (!isset($_SESSION['role']) || (int)$_SESSION['role'] !== 2) {
    header("Location:/mini_pro/view/auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Document</title>
</head>

<body>
   <h2>hello , <?php if(isset($_COOKIE['name'])) echo $_COOKIE['name']?> welcome to tournaments</h2> 
    <button id="load">Tournaments Data</button><br><br>
    <div id="data"></div><br><br>
    <p id="error"></p>


    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>

    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/tournament/load_data.php') ?>
</body>




</html>