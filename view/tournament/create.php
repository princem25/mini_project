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

    <a href="../admin/admin_dash.php">Admin dashboard</a><br><br><br>
     
        Tournament Name : <input type="text" id="name"><br><br>
        Tournament start : <input type="date" id="start_date"><br><br>
        Tournament end : <input type="date" id="end_date"><br><br>
        Tournament Type : <select id="type">
            <option value="knockout">knockout</option>
            <option value="league">league</option>
        </select><br><br>
        Tournament Status : <select id="status">
            <option value="upcoming">upcoming</option>
        </select><br><br>
 <p id="error"></p>
    <p id="success"></p>
        <button id="btn">submit</button>
     <br><br>
   
    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>

    
     <script>
        $(document).ready(function() {
            $("#btn").click(function() {
                
                var name = $("#name").val();
                var start = $("#start_date").val();
                var end = $("#end_date").val();
                var type = $("#type").val();
                var status = $("#status").val();

                console.log(name,start,end,type,status);
                
               

                if (name === "" || start === "" || end === "" || type === "" || status === "") {
                    $("#error").html("all fields are required");
                } else {
                    $("#error").html("");
                     console.log("req sending");
                    $.post("/mini_pro/controller/tour_control.php", {
                            name,
                            start,
                            end,
                                type,
                                status
                        },
                        function(response) {
                              console.log("res come");
                            console.log(response);
                            if (response.status === "success") {
                                $("#success").html(response.message);
                            } else {
                                $("#error").html(response.message);
                            }

                        },"json")
                }
            });
        })
    </script>
</body>

</html>