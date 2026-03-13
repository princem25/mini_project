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
    <title>Create Team</title>
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
               
                <label>Select verified Tournament</label>
                <select id="tourselect">
                    <option value="">-- Select  Tournament --</option>
                </select>
                <div id="scheduleInfo" style="margin-top: 12px; font-size: 0.9em; color: #333;"></div>
                <label>Team1</label>
                <select class="teamSelect1">
                    <option value="">-- Select Team1 --</option>
                </select>
                <label>Team2</label>
                <select class="teamSelect2">
                    <option value="">-- Select Team2 --</option>
                </select>
                 <label>Match Date</label>
                <input type="date" id="date">
                  <label>match status</label>
                <select class="status">
                    <option value="Upcoming">Upcoming   </option>
                </select>
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Create</button>
            <button id="btn-generate" style="background-color: #f39c12; margin-left: 10px;">Auto-Generate League Matches</button>
        </div>

        <?php require_once('C:/xampp_3/htdocs/mini_project/view/auth/logout.php') ?>
    </div>

    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var tourid = $("#tourselect").val();
                var team1 = $(".teamSelect1").val();
                var team2 = $(".teamSelect2").val();
                var date = $("#date").val();
                var status = $(".status").val();

                console.log(tourid);
                
              
 
                if (tourid == "" || team1 == "" || team2 == "" || date == "" || team1 === team2) {

                    $("#error").html("all fields are required or both team are same");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");
                    console.log("send");
                    
                    $.post("/mini_project/controller/match/create.php", {
                           tourid,team1,team2,date,status
                        },
                        function(response) {
                               console.log(response);
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

            $("#btn-generate").click(function() {
                var tourid = $("#tourselect").val();
                if (tourid == "") {
                    $("#error").html("Please select a Tournament to auto-generate matches");
                    $("#success").html("");
                } else {
                    $("#error").html("Generating matches...");
                    $("#success").html("");
                    $.post("/mini_project/controller/match/autogenerate.php", { tourid }, function(response) {
                        if (response.status === "success") {
                            $("#success").html(response.message);
                            $("#error").html("");
                        } else {
                            $("#error").html(response.message);
                            $("#success").html("");
                        }
                    }, "json");
                }
            });

            // LOAD BUTTON

            $("#tourselect").change(function() {
                var tourid = $(this).val();
                if (tourid) {
                    updateScheduleInfo(tourid);
                } else {
                    $("#scheduleInfo").html("");
                }
            });
        });
        </script>
         <script>
      
            function updateScheduleInfo(tourid) {
                $("#scheduleInfo").html("Checking schedule...");
                $.post("/mini_project/controller/match/autogenerate_info.php", { tourid }, function(response) {
                    if (response.status === "success") {
                        var msg = `Tournament duration: ${response.available_days} day(s). ` +
                            `${response.teams} team(s) => ${response.total_matches} total matches (1 per day).`;

                        if (!response.can_generate) {
                            msg += ` You need at least ${response.required_days} days. Max teams for this duration: ${response.max_teams}.`;
                        } else {
                            msg += " This tournament duration is sufficient to generate the schedule.";
                        }

                        $("#scheduleInfo").html(msg);
                    } else {
                        $("#scheduleInfo").html(response.message || "Unable to fetch schedule info");
                    }
                }, "json").fail(function() {
                    $("#scheduleInfo").html("Unable to fetch schedule info");
                });
            }

            // Load teams into dropdown
            function loadTours() {
               
                $.get("/mini_project/controller/tournament/listverified.php", function(response) {

                    if (response.status === "success") {

                        let options = '<option value="">-- Select Tour --</option>';

                        response.data.forEach(function(tour) {
                            options += `<option value="${tour.tour_id}">
                                    ${tour.tour_name},
                                    ${tour.tour_id}
                                </option>`;
                        });

                        $("#tourselect").html(options);
                    }

                }, "json");
            }


            loadTours();

    </script>
    <script>
 

    // Load teams into dropdown
    function loadTeams() {
        $.get("/mini_project/controller/team/matchset.php", function (response) {

            if (response.status === "success") {

                let options = '<option value="">-- Select Team --</option>';

                response.data.forEach(function(team) {
                    options += `<option value="${team.team_id}">
                                    ${team.team_name},
                                    ${team.tour_id}
                                </option>`;
                });

                $(".teamSelect1").html(options);
                 $(".teamSelect2").html(options);
            }

        }, "json");
    }

    
    loadTeams();

 
</script>
        <?php require_once('C:/xampp_3/htdocs/mini_project/view/match/load.php') ?>
</body>

</html>
