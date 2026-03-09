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
    <title>Update Team</title>
</head>

<body>
    <div class="wrapper">
        <div class="breadcrumb">
            <a href="../admin/dashboard.php">Admin Dashboard</a>
            <a href="../team/dashboard.php">Team Dashboard</a>
        </div>

        <h2>Update Team</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="loadteams">Load Teams</button>
        <div id="datateam"></div>

        <div class="section">
            <div class="form-group">
                <label>Select Team</label>
                <select id="teamSelect">
                    <option value="">-- Select Team --</option>
                </select>
            </div>
            <div class="form-group">
                <label>Team Name</label>
                <input type="name" id="name">
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
                var id = $("#teamSelect").val();
                var name = $("#name").val();


                if (id == "" || name == "") {
                    $("#error").html("all fields are required");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");
                    console.log(id);



                    $.post("/mini_pro/controller/team/update.php", {
                            id,
                            name
                        },
                        function(response) {

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
    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/team/load_select.php') ?>

    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/team/load.php') ?>
</body>

</html>