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
    <a href="../team/teamdash.php">team dashboard</a><br><br><br>
    <button id="loadteams">Load Teams</button><br><br>
    <div id="datateam"></div><br><br>

    Select Team:
    <select id="teamSelect">
        <option value="">-- Select Team --</option>
    </select><br><br>

    team name : <input type="name" id="name"><br><br>


    <p id="error"></p>
    <p id="success"></p>
    <button id="btn">update</button>
    <br><br>

    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>


    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var id = $("#teamSelect").val();
                var name = $("#name").val();


                if (id == "", name == "") {
                    $("#error").html("all fields are required");
                } else {
                    $("#error").html("");
                    console.log(id);



                    $.post("/mini_pro/controller/teamcontroller/updateteam.php", {
                            id,
                            name
                        },
                        function(response) {

                            if (response.status === "success") {
                                $("#success").html(response.message);
                                loadTeams();
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