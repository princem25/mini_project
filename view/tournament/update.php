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
    <title>Update Tournament</title>
</head>

<body>
    <div class="wrapper">
        <div class="breadcrumb">
            <a href="../admin/dashboard.php">Admin Dashboard</a>
            <a href="../tournament/dashboard.php">Tournament Dashboard</a>
        </div>

        <h2>Update Tournament</h2>
        <p class="subtitle">Welcome, <?php if (isset($_COOKIE['name'])) echo strtoupper($_COOKIE['name']); ?></p>

        <button id="load">Load Tournaments</button>
        <div id="data"></div>

        <div class="section">
            <div class="form-group">
                <label>Select Tournament</label>
                <select id="tourselect">
                    <option value="">-- Select Tournament --</option>
                </select>
            </div>
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

    </div>

    <script>
        $(document).ready(function() {

            $("#btn").click(function() {
                var id = $("#tourselect").val();
                var name = $("#name").val();
                var start = $("#start_date").val();
                var end = $("#end_date").val();
                var type = $("#type").val();
                var status = $("#status").val();
                var nameRegex = /^[a-zA-Z0-9 ]+$/;

                if (id == "" || name == "" || start == "" || end == "" || type == "" || status == "") {
                    $("#error").html("all fields are required");
                    $("#success").html("");
                } else if (!nameRegex.test(name)) {
                    $("#error").html("Special characters are not allowed in name");
                    $("#success").html("");
                } else if (new Date(end) < new Date(start)) {
                    $("#error").html("End date must be the same as or after the start date.");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");

                    $.post("/mini_pro/controller/tournament/update.php", {
                            id,
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
                                loadTours();
                            } else {
                                $("#error").html(response.message);
                                $("#success").html("");
                            }
                        },
                        "json"
                    );
                }
            });

            // Restrict end date selection
            $("#start_date").change(function() {
                var startDate = $(this).val();
                $("#end_date").attr("min", startDate);
                
                // If end date is now before the new start date, clear it
                var endDate = $("#end_date").val();
                if (endDate && endDate < startDate) {
                    $("#end_date").val("");
                }
            });

        });
    </script>

    <script>
        // Load teams into dropdown
        function loadTours() {

            $.get("/mini_pro/controller/tournament/list.php", function(response) {

                if (response.status === "success") {

                    let options = '<option value="">-- Select Tour --</option>';
                    window.tournamentData = response.data; // Store globally for easy access

                    response.data.forEach(function(tour) {
                        options += `<option value="${tour.tour_id}">
                                    ${tour.tour_name}
                                </option>`;
                    });

                    $("#tourselect").html(options);
                }

            }, "json");
        }

        $("#tourselect").change(function() {
            var id = $(this).val();
            if (id && window.tournamentData) {
                var tour = window.tournamentData.find(t => t.tour_id == id);
                if (tour) {
                    $("#name").val(tour.tour_name);
                    $("#start_date").val(tour.start_date);
                    $("#end_date").val(tour.end_date).attr("min", tour.start_date);
                    $("#type").val(tour.type);
                    $("#status").val(tour.status);
                }
            } else {
                $("#name").val("");
                $("#start_date").val("");
                $("#end_date").val("").removeAttr("min");
            }
        });

        loadTours();
    </script>
    <?php require_once('C:/xampp_3/htdocs/mini_pro/view/tournament/load.php') ?>
</body>

</html>