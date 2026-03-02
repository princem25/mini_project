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
    <a href="../Team/teamdash.php">Team dashboard</a><br><br><br>
    <button id="loadteams">Load Teams</button><br><br>
    <div id="datateam"></div><br><br>

    Team Name : <input type="text" id="name"><br><br>
 
    <p id="error"></p>
    <p id="success"></p>
    <button id="btn">submit</button>
    <br><br>

    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>


    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var name = $("#name").val();
              

                if (name === "") {
                    $("#error").html("all fields are required");
                } else {
                    $("#error").html("");
                    console.log("send");
                    
                    $.post("/mini_pro/controller/teamcontroller/regteam.php", {
                            name
                        },
                        function(response) {
                               console.log("return");
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

            // LOAD BUTTON
        });
        </script>
        <?php require_once('loaddata.php') ?>
</body>

</html>