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
            <title>Assign Team</title>
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

                <h2>Assign Team to Tournament</h2>
                <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

                <button id="loadteams">Load Teams</button>
                <div id="datateam"></div>

                <div class="section">
                    <div class="form-group">
                        <label>Select Tournament</label>
                        <select id="tourselect">
                            <option value="">-- Select Tournament --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select verified Team</label>
                        <select id="teamSelect">
                            <option value="">-- Select Team --</option>
                        </select>
                    </div>

                    <p id="error"></p>
                    <p id="success"></p>
                    <p class="note">Note: If a team is already part of any tournament, it won't appear in the dropdown.</p>
                    <button id="btn">Assign</button>
                </div>

                <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>
            </div>

            <script>
                $(document).ready(function() {

                    $("#btn").click(function() {
                        var tourid = $("#tourselect").val();
                        var teamid = $("#teamSelect").val();

                        console.log("send");
                        


                        if (tourid == "" || teamid == "") {
                            $("#error").html("all fields are required");
                            $("#success").html("");
                        } else {
                            $("#error").html("");
                            $("#success").html("");
        
                            $.post("/mini_pro/controller/team/assign.php", {
                                    tourid,
                                    teamid
                                },
                                function(response) {
                                    console.log(response);
                                    if (response.status === "success") {
                                        $("#success").html(response.message);
                                        $("#error").html("");
                                        loadTeams();
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




            <script>
                // Load teams into dropdown
                function loadTeams() {
                    $.get("/mini_pro/controller/team/available.php", function(response) {

                        if (response.status === "success") {

                            let options = '<option value="">-- Select Team --</option>';

                            response.data.forEach(function(team) {
                                options += `<option value="${team.team_id}">
                                        ${team.team_name}
                                    </option>`;
                            });

                            $("#teamSelect").html(options);
                        }

                    }, "json");
                }


                loadTeams();
            </script>




            <?php require_once('C:/xampp_3/htdocs/mini_pro/view/team/load.php') ?>
            <?php require_once('C:/xampp_3/htdocs/mini_pro/view/tournament/nonverified.php') ?>

        </body>

        </html>