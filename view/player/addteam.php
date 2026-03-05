    <?php
    require_once('C:/xampp_new/htdocs/mini_pro/view/admin/sessionAdmin.php');
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <h2>welcome , <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']) ?></h2>

        <a href="../admin/admin_dash.php">Admin dashboard</a><br> <br>
        <a href="../team/teamdash.php">team dashboard</a><br><br>
        <a href="../player/playerdash.php">player dashboard</a><br><br><br>
        <button id="loadteams">Load Teams</button><br><br>
        <div id="datateam"></div><br><br>

        Select Team:
        <select id="teamSelect">
            <option value="">-- Select Team --</option>
        </select><br><br>


        Select player:
        <select id="playerselect">
            <option value="">-- Select player --</option>
        </select>


        <p id="error"></p>
        <p id="success"></p>
        <p>note : if player is already part of any team then not shown in dropdown</p>
        <button id="btn">Assign</button>
        <br><br>

        <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>



        <script>
            // Load teams into dropdown
            function loadplayers() {
                $.get("/mini_pro/controller/playercontroller/player_fresh.php", function(response) {

                    if (response.status === "success") {

                        let options = '<option value="">-- Select Team --</option>';

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


                    if (teamid == "" || userid == "") {
                        $("#success").html("");
                        $("#error").html("all fields are required");
                    } else {
                        $("#error").html("");

                        $.post("/mini_pro/controller/playercontroller/player_assign_team.php", {
                                teamid,
                                userid
                            },
                            function(response) {

                                if (response.status === "success") {
                                    $("#success").html(response.message);
                                    loadplayers();
                                } else {
                                    $("#error").html(response.message);
                                }
                            },
                            "json"
                        );
                    }
                });

            });
        </script>






        <?php require_once('C:/xampp_new/htdocs/mini_pro/view/team/loadteam.php') ?>
        <?php require_once('C:/xampp_new/htdocs/mini_pro/view/team/loaddata.php') ?>

    </body>

    </html>