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

        <h2>Match Score update</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="loadmatches">Load matches</button>
        <div id="datamatch"></div><br>

        <button id="matchscore">Load match_score</button>
        <div id="datamatchscore"></div>

        <div class="section">
            <div class="form-group">

                <label>Select Match ID</label>
                <select class="matchselect">
                    <option value="">-- Select match --</option>
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
                    <option value="Draw">Draw</option>
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
            let team1id = "";
            let team2id = "";

            $("#btn").click(function() {
                var matchid = $(".matchselect").val();
                var team1 = $(".team1").val();
                var team2 = $(".team2").val();
                var winner = $(".winner").val();
            

                if (matchid == "" || team1 == "" || team2 == "" || winner == "") {

                    $("#error").html("all fields are required");
                    $("#success").html("");

                } else {
                    console.log("send");
                    
                    $("#error").html("");
                    $("#success").html("");

                    $.post("/mini_pro/controller/score/update.php", {
                            matchid,
                            team1,
                            team2,
                            winner,
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
                                $(".winner").html(""); // clear old options
                                team1id = response.data.team1_id;
                                team2id = response.data.team2_id;

                                // Keep Draw option
                                $(".winner").append('<option value="Draw">Draw</option>');

                                $(".winner").append(
                                    '<option value="' + response.data.team1_id + '">' + "Team 1 ID : " + response.data.team1_id + '</option>'

                                );

                                $(".winner").append(
                                    '<option value="' + response.data.team2_id + '">' + "Team 2 ID : " + response.data.team2_id + '</option>'
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

        });
    </script>


    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/match/load.php') ?>
    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/match/loadmatches.php') ?>
    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/match_score/load.php') ?>

</body>

</html>