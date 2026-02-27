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
      <a href="../tournament/tour_dash.php">tournament dashboard</a><br><br><br>
    <button id="load">Load Tournaments</button><br><br>
   <div id="data"></div><br><br>
     
        Tournament id : <input type="number" id="id"><br><br>
       Tournament Status : <select id="status">
         
        <option value="Upcoming">Upcoming</option>
        <option value="ongoing">ongoing</option>
        <option value="completed">completed</option>
    </select><br><br>
        
        <p>notice : keep id same as shown in load data</p>
 <p id="error"></p>
    <p id="success"></p>
        <button id="btn">update</button>
     <br><br>
   
    <?php require_once('C:/xampp_new/htdocs/mini_pro/view/auth/logout.php') ?>

    
     <script>
      $(document).ready(function() {

    $("#btn").click(function() {
        var id = $("#id").val();
        var status = $("#status").val();
        

        if (id == "" || status== "") {
            $("#error").html("all fields are required");
        } else {
            $("#error").html("");

            $.post("/mini_pro/controller/tourcontroller/tour_status.php", {
                    id ,
                    status
                },
                function(response) {
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
     
});
</script>
<?php require_once('C:/xampp_new/htdocs/mini_pro/view/tournament/load_data.php') ?>
</body>

</html>