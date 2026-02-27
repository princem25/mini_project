<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>registration</title>
</head>

<body>

    <h1>Registration form</h1>

    Username :
    <input type="text" name="name" required id="name"><br><br>
    Password :
    <input type="password" name="pass" required id="pass"><br><br>
    Role :
    <input type="radio" value=1 name="role" class="role" required>Admin
    <input type="radio" value=2 name="role" class="role" required>Player
    <br><br>
    <p id="error"></p>
    <input type="button" name="btn" value="register" id="button">
         <a href="/mini_pro/view/auth/login.php">login</a>

    <script>
        $(document).ready(function() {
            $("#button").click(function() {
                

                var name = $("#name").val();
                var pass = $("#pass").val();
                var role = $("input[name='role']:checked").val();

                if (name === "" || pass === "" || role === "") {
                    $("#error").html("all fields are required");
                } else {
                    $("#error").html("");
                    console.log("sending request...");  
                    $.post("/mini_pro/controller/authcontroller/reg_control.php", {
                            name,
                            pass,
                            role
                        },
                        function(response) {
                          
                            
                            console.log("SERVER RESPONSE:", response);
                            if (response.status === "exists") {
                               window.location.href = "/mini_pro/view/auth/login.php";
                            } else if (response.status === "registered") {
                                $("#error").html("Registration successful");
                            } else {    
                                $("#error").html("Something went wrong");
                            }

                        },"json");
                }
            })
        });
    </script>

</body>

</html>