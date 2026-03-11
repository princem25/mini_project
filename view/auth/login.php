<?php
session_start();

if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 1)
        header("Location:/mini_pro/view/admin/dashboard.php");
    else
        header("Location:/mini_pro/view/player_view/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/mini_pro/assets/style.css">
    <title>Login</title>
</head>

<body class="auth-page">
    <div class="wrapper">
        <div class="auth-icon">🔒</div>
        <h2>Login</h2>
        <p class="subtitle">Sign in to your account</p>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required id="email">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="pass" required id="pass">
        </div>
        <div class="form-group">
            <label>Role</label>
            <div class="radio-group">
                <label><input type="radio" value=1 name="role" class="role" required> Admin</label>
                <label><input type="radio" value=2 name="role" class="role" required> Player</label>
            </div>
        </div>
        <p id="error"></p>
        <button id="login" class="btn-block">Login</button>

        <div class="auth-footer">
            Don't have an account? <a href="/mini_pro/view/auth/register.php">Register</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#login").click(function() {
              
                var email = $("#email").val();
                var pass = $("#pass").val();
                var role = $("input[name='role']:checked").val();

                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email === "" || pass === "" || role === undefined) {
                    $("#error").html("All fields are required");
                    $("#success").html("");
                } else if (!emailRegex.test(email)) {
                    $("#error").html("Please enter a valid email address");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");
                     console.log("req sending");
                    $.post("/mini_pro/controller/auth/login.php", {
                            email,
                            pass,
                            role
                        },
                        function(response) {
                              console.log("res come");
                            console.log(response);
                            if (response.status === "success") {
                                if(response.role === 1) window.location.href = "/mini_pro/view/admin/dashboard.php";
                                else if(response.role  === 2) window.location.href = "/mini_pro/view/player_view/dashboard.php";
                            } else {
                                $("#error").html(response.message);
                                $("#success").html("");
                            }

                        },"json")
                }
            });
        })
    </script>


</body>

</html>