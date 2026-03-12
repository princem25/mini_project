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
    <title>Create Team</title>
</head>

<body>
    <div class="wrapper">
        <div class="breadcrumb">
              <a href="/mini_pro/view/admin/dashboard.php">Admin</a> | 
            <a href="/mini_pro/view/player/dashboard.php">Player</a> | 
            <a href="/mini_pro/view/team/dashboard.php">Team</a> | 
            <a href="/mini_pro/view/tournament/dashboard.php">Tournament</a> | 
            <a href="/mini_pro/view/match/dashboard.php">Match</a> | 
            <a href="/mini_pro/view/match_score/dashboard.php">Score</a> | 
            <a href="/mini_pro/view/leaderboard/dashboard.php">Leaderboard</a>
        </div>

        <h2>Create Team</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="loadteams">Load Teams</button>
        <div id="datateam"></div>

        <div class="section">
            <div class="form-group">
                <label>Team Name</label>
                <input type="text" id="name">
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Submit</button>
        </div>

        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>
    </div>

    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var name = $("#name").val();
                var nameRegex = /^[a-zA-Z0-9 ]+$/;
 
                if (name == "") {
 

                    $("#error").html("all fields are required");
                    $("#success").html("");
                } else if (!nameRegex.test(name)) {
                    $("#error").html("Special characters are not allowed in name");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");
                    console.log("send");
                    
                    $.post("/mini_pro/controller/team/create.php", {
                            name
                        },
                        function(response) {
                               console.log("return");
                            if (response.status === "success") {
                                $("#success").html(response.message);
                                $("#error").html("");
                            } else {
                                $("#error").html(response.message);
                                $("#success").html("");
                            }
                        },
                        "json"
                    );
                }
            });

            // LOAD BUTTON
        });
        </script>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/team/load.php') ?>
</body>

</html>