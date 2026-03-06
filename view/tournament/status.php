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
    <title>Change Tournament Status</title>
</head>

<body>
    <div class="wrapper">
        <div class="breadcrumb">
            <a href="../admin/dashboard.php">Admin Dashboard</a>
            <a href="../tournament/dashboard.php">Tournament Dashboard</a>
        </div>

        <h2>Change Tournament Status</h2>
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
                <label>Tournament Status</label>
                <select id="status">
                    <option value="Upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <p id="error"></p>
            <p id="success"></p>
            <button id="btn">Update</button>
        </div>

        <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>
    </div>

     <script>
      $(document).ready(function() {

    $("#btn").click(function() {
        var id = $("#tourselect").val();
        var status = $("#status").val();
        

        if (id == "" || status == "") {
            $("#error").html("all fields are required");
            $("#success").html("");
        } else {
            $("#error").html("");
            $("#success").html("");

            $.post("/mini_pro/controller/tournament/status.php", {
                    id,
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
     
});
</script>


    <script>
      

            // Load teams into dropdown
            function loadTours() {
               
                $.get("/mini_pro/controller/tournament/list.php", function(response) {

                    if (response.status === "success") {

                        let options = '<option value="">-- Select Tour --</option>';

                        response.data.forEach(function(tour) {
                            options += `<option value="${tour.tour_id}">
                                    ${tour.tour_name}
                                </option>`;
                        });

                        $("#tourselect").html(options);
                    }

                }, "json");
            }


            loadTours();

      
    </script>

 
<?php require_once('C:/xampp_new/htdocs/mini_pro/view/tournament/load.php') ?>
</body>

</html>