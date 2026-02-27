<?php
if(session_status() !== PHP_SESSION_ACTIVE){
session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location:/mini_pro/view/auth/login.php");
    exit;
}

if (!isset($_SESSION['role'])) {
    header("Location:/mini_pro/view/auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Document</title>
</head>

<body>
  
   
    <button id="logout">Logout</button>

    <script>
        $(document).ready(function() {

            $("#logout").click(function() {
                $.get("/mini_pro/controller/logout_control.php", function(resp) {
                    console.log(resp);

                    if (resp.status === "ok") {
                        window.location.href = "/mini_pro/view/auth/login.php";
                    } else {
                        $("#error").html(resp.message);
                    }

                }, "json");
            });

        });


    </script>
</body>

</html>