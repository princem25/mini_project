<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/mini_pro/assets/style.css">
    <title>Registration</title>
</head>

<body class="auth-page">
    <div class="wrapper">
        <div class="auth-icon">📝</div>
        <h2>Registration</h2>
        <p class="subtitle">Create a new account</p>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="name" required id="name">
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
        <p id="success"></p>
        <input type="button" name="btn" value="Register" id="button" class="btn-block">

        <div class="auth-footer">
            Already have an account? <a href="/mini_pro/view/auth/login.php">Login</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#button").click(function() {
                

                var name = $("#name").val();
                var pass = $("#pass").val();
                var role = $("input[name='role']:checked").val();

                if (name === "" || pass === "" || role === "") {
                    $("#error").html("all fields are required");
                    $("#success").html("");
                } else {
                    $("#error").html("");
                    $("#success").html("");
                    console.log("sending request...");  
                    $.post("/mini_pro/controller/auth/register.php", {
                            name,
                            pass,
                            role
                        },
                        function(response) {
                          
                            console.log("SERVER RESPONSE:", response);
                             if (response.status === "exists") {
                                 $("#error").html("Username already registered");
                                 $("#success").html("");
                            } else if (response.status === "registered") {
                                $("#success").html("Registration successful");
                                $("#error").html("");
                            } else {    
                                $("#error").html("Something went wrong");
                                $("#success").html("");
                            }

                        });
                }
            })
        });
    </script>

</body>

</html>