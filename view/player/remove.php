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
    <title>Remove Player from Team</title>
</head>

<body>
    <div class="wrapper">
        <div class="breadcrumb">
            <a href="../admin/dashboard.php">Admin Dashboard</a>
            <a href="../team/dashboard.php">Team Dashboard</a>
        </div>

        <h2>Remove Player from Team</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="loadplayer">Load Players</button>
        <div id="dataplayer"></div>

        <div class="section">
            <div class="form-group">
                <label>Select Player</label>
                <select id="playerSelect">
                    <option value="">-- Select player --</option>
                </select>
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Delete</button>
        </div>

        <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>
    </div>

    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var playerid = $("#playerSelect").val();


                if (playerid == "") {
                    $("#error").html("all fields are required");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");

                    $.post("/mini_pro/controller/player/remove.php", {
                            playerid
                        },
                        function(response) {

                            if (response.status === "success") {
                                 $("#error").html("");
                                $("#success").html(response.message);
                                loadPlayer();
                            } else {
                                $("#success").html("");
                                $("#error").html(response.message);
                            }
                        },
                        "json"
                    );
                }
            });

        });
    </script>

        
<script>
 

    // Load teams into dropdown
    function loadPlayer() {
        $.get("/mini_pro/controller/player/assigned.php", function (response) {

            if (response.status === "success") {

                let options = '<option value="">-- Select player --</option>';

                response.data.forEach(function(player) {
                    options += `<option value="${player.player_id}">
                                ${player.player_id}  ${player.name} , ${player.team_name}
                                </option>`;
                });

                $("#playerSelect").html(options);
            }

        }, "json");
    }

    
    loadPlayer();

 
</script>

        <?php require_once('C:/xampp_new/htdocs/mini_pro/view/player/load.php') ?>
</body>

</html>