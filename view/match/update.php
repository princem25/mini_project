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
    <title>update</title>
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

        <h2>Create Match</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="loadmatches">Load matches</button>
        <div id="datamatch"></div>

        <div class="section">
            <div class="form-group">
               
                
                <label>select match</label>
                <select class="matchselect">
                    <option value="">-- Select match --</option>
                </select>
               
                  <label>match status</label>
                <select class="status">
                    <option value="Upcoming">Upcoming   </option>
                    <option value="Ongoing">Ongoing   </option>
                    <option value="Completed">Completed   </option>
                </select>
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Update</button>
            <button id="btn-delete" style="background-color: #e74c3c; margin-left: 10px;">Delete Match</button>
        </div>

        <?php require_once('C:/xampp_3/htdocs/mini_project/view/auth/logout.php') ?>
    </div>

    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var matchid = $(".matchselect").val();
                var status = $(".status").val();
      
                if (matchid == "" || status == "") {

                    $("#error").html("all fields are required");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");
                    console.log("send");
                    
                    $.post("/mini_project/controller/match/update.php", {
                        matchid,status
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

            $("#btn-delete").click(function() {
                var matchid = $(".matchselect").val();
      
                if (matchid == "") {
                    $("#error").html("Please select a match to delete");
                    $("#success").html("");
                } else if (!confirm("Are you sure you want to delete this match? This will also delete associated scores.")) {
                    return;
                } else {
                    $("#error").html("");
                    $("#success").html("");
                    
                    $.post("/mini_project/controller/match/delete.php", {
                        id: matchid
                        },
                        function(response) {
                            if (response.status === "success") {
                                $("#success").html(response.message);
                                $("#error").html("");
                                // Reload matches
                                location.reload();
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
   
 
        <?php require_once('C:/xampp_3/htdocs/mini_project/view/match/load.php') ?>
        <?php require_once('C:/xampp_3/htdocs/mini_project/view/match/loadmatches.php') ?>
</body>

</html>
