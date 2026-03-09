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
    <title>Create Tournament</title>
</head>

<body>
    <div class="wrapper">
        <div class="breadcrumb">
            <a href="../admin/dashboard.php">Admin Dashboard</a>
            <a href="../tournament/dashboard.php">Tournament Dashboard</a>
        </div>

        <h2>Create Tournament</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="load">Load Tournaments</button>
        <div id="data"></div>

        <div class="section">
            <div class="form-group">
                <label>Tournament Name</label>
                <input type="text" id="name">
            </div>
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" id="start_date">
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" id="end_date">
            </div>
            <div class="form-group">
                <label>Tournament Type</label>
                <select id="type">
                    <option value="knockout">Knockout</option>
                    <option value="league">League</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tournament Status</label>
                <select id="status">
                    <option value="upcoming">Upcoming</option>
                </select>
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Submit</button>
        </div>

        <?php require_once('C:/xampp_3/htdocs/mini_pro/view/auth/logout.php') ?>
    </div>

    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var name = $("#name").val();
                var start = $("#start_date").val();
                var end = $("#end_date").val();
                var type = $("#type").val();
                var status = $("#status").val();
 
                if (name == "" || start == "" || end == "" || type == "" || status == "") {
 
                    $("#error").html("all fields are required");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");

                    $.post("/mini_pro/controller/tournament/create.php", {
                            name,
                            start,
                            end,
                            type,
                            status
                        },
                        function(response) {
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

            // LOAD BUTTON
        });
    </script>
    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/tournament/load.php') ?>
</body>

</html>