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
        <title>Add Player to Team</title>
    </head>

    <body>
        <div class="wrapper">
            <div class="breadcrumb">
                <a href="../admin/dashboard.php">Admin Dashboard</a>
                <a href="../team/dashboard.php">Team Dashboard</a>
                <a href="../player/dashboard.php">Player Dashboard</a>
            </div>

            <h2>Add Player to Team</h2>
            <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

            <button id="loadteams">Load Teams</button>
            <div id="datateam"></div>

            <div class="section">
                <div class="form-group">
                    <label>Select Team</label>
                    <select id="teamSelect">
                        <option value="">-- Select --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Player</label>
                    <select id="playerselect">
                        <option value="">-- Select --</option>
                    </select>
                </div>

                <p id="error"></p>
                <p id="success"></p>
                <p class="note">Note: If a player is already part of any team, they won't appear in the dropdown.</p>
                <button id="btn">Assign</button>
            </div>

            <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>
        </div>



        <script>
            // Load teams into dropdown
            function loadplayers() {
                $.get("/mini_pro/controller/player/unassigned.php", function(response) {

                    if (response.status === "success") {

                        let options = '<option value="">-- Select player --</option>';

                        response.data.forEach(function(player) {
                            options += `<option value="${player.user_id}">
                                    ${player.name}
                                </option>`;
                        });

                        $("#playerselect").html(options);
                    }

                }, "json");
            }


            loadplayers();
        </script>
        <script>
            $(document).ready(function() {




                $("#btn").click(function() {

                    var teamid = $("#teamSelect").val();
                    var userid = $("#playerselect").val();

                    console.log(teamid, userid);
                    if (teamid == "" || userid == "") {
                        $("#error").html("all fields are required");
                        $("#success").html("");
                    } else {
                        $("#error").html("");
                        $("#success").html("");

                        $.post("/mini_pro/controller/player/assign.php", {
                                teamid,
                                userid
                            },
                            function(response) {
                                if (response.status === "success") {
                                    $("#success").html(response.message);
                                    $("#error").html("");
                                    loadplayers();
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




        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/team/load_select.php') ?>
        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/team/load.php') ?>

    </body>

    </html>