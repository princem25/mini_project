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
    <link rel="stylesheet" href="/mini_project/assets/style.css">
    <title>Create score</title>
</head>

<body>
    <div class="wrapper">
        <div class="breadcrumb">
              <a href="/mini_project/view/admin/dashboard.php">Admin</a> | 
            <a href="/mini_project/view/player/dashboard.php">Player</a> | 
            <a href="/mini_project/view/team/dashboard.php">Team</a> | 
            <a href="/mini_project/view/tournament/dashboard.php">Tournament</a> | 
            <a href="/mini_project/view/match/dashboard.php">Match</a> | 
            <a href="/mini_project/view/match_score/dashboard.php">Score</a> | 
            <a href="/mini_project/view/leaderboard/dashboard.php">Leaderboard</a>
        </div>

        <h2>Match Score</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="loadmatches">Load matches</button>
        <div id="datamatch"></div><br>

        <button id="matchscore">Load match_score</button>
        <div id="datamatchscore"></div>

        <div class="section">
            <div class="form-group">

                <label>select match</label>
                <select class="matchselect">
                    <option value="">-- Select match --</option>
                </select>

                </select>

                <label>team1_score</label>
                <input type="number" class="team1" min="0" placeholder="Enter Team 1 Score">

                <label>team2_score</label>
                <input type="number" class="team2" min="0" placeholder="Enter Team 2 Score">
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Update</button>
        </div>

        <?php require_once('C:/xampp_3/htdocs/mini_project/view/auth/logout.php') ?>
    </div>

    <script>
        $(document).ready(function() {
            let team1id = "";
            let team2id = "";

            $("#btn").click(function() {
                var matchid = $(".matchselect").val();
                var team1 = $(".team1").val();
                var team2 = $(".team2").val();

                if (matchid == "" || team1 == "" || team2 == "" || team1 < 0 || team2 < 0) {

                    $("#error").html("all fields are required and scores cannot be negative");
                    $("#success").html("");

                } else {

                    $("#error").html("");
                    $("#success").html("");

                    $.post("/mini_project/controller/score/create.php", {
                            matchid,
                            team1,
                            team2,
                            team1id,
                            team2id

                        },
                        function(response) {

                            if (response.status === "success") {
                                $("#success").html(response.message);
                            } else {
                                $("#error").html(response.message);
                            }

                        },
                        "json"
                    );
                }
            });


            // MATCH CHANGE AJAX
            $(".matchselect").change(function() {

                console.log("called");

                var matchid = $(this).val();

                if (matchid != "") {

                    $.post("/mini_project/controller/match/matchfromid.php", {
                            id: matchid
                        },
                        function(response) {

                            console.log(response);
                            if (response.status === "success") {
                                team1id = response.data.team1_id;
                                team2id = response.data.team2_id;
                            } else {

                                console.log("Failed:", response.message);

                                $("#error").html(response.message || "Unable to load teams for this match.");
                                $("#success").html("");
                            }

                        },
                        "json"
                    );

                }

            });

        });
    </script>


    <?php require_once('C:/xampp_3/htdocs/mini_project/view/match/load.php') ?>
    <?php require_once('C:/xampp_3/htdocs/mini_project/view/match/loadmatches.php') ?>
    <?php require_once('C:/xampp_3/htdocs/mini_project/view/match_score/load.php') ?>

</body>

</html>
