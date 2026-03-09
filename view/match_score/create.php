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
            <a href="../admin/dashboard.php">Admin Dashboard</a>
            <a href="../tournament/dashboard.php">Tournament Dashboard</a>
            <a href="../Team/dashboard.php">Team Dashboard</a>
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
                <select class="team1">
                    <option value="5">5</option>
                    <option value="10">10</option>
                </select>

                <label>team2_score</label>
                <select class="team2">
                    <option value="5">5</option>
                    <option value="10">10</option>
                </select>
                <label>winner team</label>

                <select class="winner">

                </select>

                <label>match status</label>
                <select class="status">
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Update</button>
        </div>

        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>
    </div>

    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var matchid = $(".matchselect").val();
                var team1 = $(".team1").val();
                var team2 = $(".team2").val();
                var winner = $(".winner").val();


                if (matchid == "" || team1 == "" || team2 == "" || winner == "") {

                    $("#error").html("all fields are required");
                    $("#success").html("");

                } else {

                    $("#error").html("");
                    $("#success").html("");

                    $.post("/mini_pro/controller/score/create.php", {
                            matchid,
                            team1,
                            team2,
                            winner

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

                    $.post("/mini_pro/controller/match/matchfromid.php", {
                            id: matchid
                        },
                        function(response) {

                            console.log(response);
                            if (response.status === "success") {
                                $("#error").html("");
                                $(".winner").html(""); // clear old options

                                $(".winner").append(
                                    '<option value="' + response.data.team1_id + '">' + response.data.team1_id + '</option>'
                                );

                                $(".winner").append(
                                    '<option value="' + response.data.team2_id + '">' + response.data.team2_id + '</option>'
                                );

                            } else {

                                console.log("Failed:", response.message);

                                $("#error").html(response.message || "Unable to load teams for this match.");
                                $("#success").html("");

                                $(".winner").html('<option value="">No teams found</option>');

                            }

                        },
                        "json"
                    );

                }

            });
            $("#btn").click(function() {
                var matchid = $(".matchselect").val();
                var status = $(".status").val();

                if (matchid == "" || status == "") {

                    $("#error").html("all fields are required ");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");
                    console.log("send");

                    $.post("/mini_pro/controller/match/update.php", {
                            matchid,
                            status
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
        });
    </script>


    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/match/load.php') ?>
    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/match/loadmatches.php') ?>
    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/match_score/load.php') ?>

</body>

</html>