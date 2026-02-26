<?php
session_start();

if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 1)
        header("Location:/mini_pro/view/admin/admin_dash.php");
    else
        header("Location:/mini_pro/view/player/player_dash.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>authentication</title>
</head>

<body>

    <h1>Login form</h1>
 Username :
    <input type="text" name="name" required id="name"><br><br>
    Password :
    <input type="password" name="pass" required id="pass"><br><br>
    Role :
    <input type="radio" value=1 name="role" class="role" required>Admin
    <input type="radio" value=2 name="role" class="role" required>Player
    <br><br>
    <p id="error"></p>
    <button id=login> Login </button>
    <a href="/mini_pro/view/auth/registration.php">register</a>

    <script>
        $(document).ready(function() {
            $("#login").click(function() {
              
                var name = $("#name").val();
                var pass = $("#pass").val();
                var role = $("input[name='role']:checked").val();

                if (name === "" || pass === "" || role === "") {
                    $("#error").html("all fields are required");
                } else {
                    $("#error").html("");
                     console.log("req sending");
                    $.post("/mini_pro/controller/login_control.php", {
                            name,
                            pass,
                            role
                        },
                        function(response) {
                              console.log("res come");
                            console.log(response);
                            if (response.status === "success") {
                                if(response.role == 1) window.location.href = "/mini_pro/view/admin/admin_dash.php";
                                else window.location.href = "/mini_pro/view/player/player_dash.php";
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