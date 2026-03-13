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
    <title>Update Score</title>
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

        <h2>Match Score update</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>


        <div class="section">
            <div class="form-group">


                <button id="matchscore">Load match_score</button><br>
                <div id="datamatchscore"></div><br>

                <label>Select Match ID</label>
                <select class="matchselect">
                    <option value="">-- Select match --</option>
                </select>

                <label>team1_score</label>
                <input type="number" class="team1" min="0" placeholder="Enter Team 1 Score">

                <label>team2_score</label>
                <input type="number" class="team2" min="0" placeholder="Enter Team 2 Score">
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Update</button>
            <button id="btn-delete-score" style="background-color: #e74c3c; margin-left: 10px;">Delete Score</button>
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
                    console.log("send");

                    $("#error").html("");
                    $("#success").html("");

                    $.post("/mini_project/controller/score/update.php", {
                            matchid,
                            team1,
                            team2,
                            team1id,
                            team2id

                        },
                        function(response) {
                            console.log("return ");

                            if (response.status === "success") {
                                $("#error").html("");
                                $("#success").html(response.message);
                            } else {
                                $("#success").html("");
                                $("#error").html(response.message);
                            }

                        },
                        "json"
                    );
                }
            });

            $("#btn-delete-score").click(function() {
                var matchid = $(".matchselect").val();

                if (matchid == "") {
                    $("#error").html("Please select a match to delete score");
                    $("#success").html("");
                } else if (!confirm("Are you sure you want to delete the score for this match?")) {
                    return;
                } else {
                    $("#error").html("");
                    $("#success").html("");

                    $.post("/mini_project/controller/score/delete.php", {
                            matchid: matchid
                        },
                        function(response) {
                            if (response.status === "success") {
                                $("#success").html(response.message);
                                $("#error").html("");
                                // Clear inputs
                                $(".team1").val("");
                                $(".team2").val("");
                            } else {
                                $("#error").html(response.message);
                                $("#success").html("");
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


    <script>
        // Load the match dropdown for selecting which match to edit
        function loadMatchDropdown() {
            $.get("/mini_project/controller/match/list.php", {
                limit: 100,
                offset: 0
            }, function(response) {
                if (response.status === "success") {
                    let options = '<option value="">-- Select match --</option>';
                    response.data.forEach(function(match) {
                        options += `<option value="${match.match_id}">${match.match_id} (T:${match.tour_id})</option>`;
                    });
                    $(".matchselect").html(options);
                }
            }, "json");
        }

        loadMatchDropdown();
    </script>
    <?php require_once('C:/xampp_3/htdocs/mini_project/view/match_score/load.php') ?>
</body>

</html>