<?php
session_start();

if (!isset($_SESSION['role']) || (int)$_SESSION['role'] !== 1) {
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

    <h3>Welcome , <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']) . "<br>You Have Admin Access" ?> </h3>

    <h4>You have access to :</h4>
    <a href="../tournament/tour_dash.php">Tournament Management</a><br><br>
    <a href="../team/teamdash.php">Team Management</a>
 <p id="error"></p>
    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>


</body>

</html>