<!DOCTYPE html>
<html lang="en">
<head>
 
    <title>authentication</title>
</head>
<body>
    <form action="/mini_pro/controller/login_control.php" method="post">
        <h1>Login form</h1>

        Username : 
        <input type="text" name="username" required><br><br>
        Password :
        <input type="password" name="password" required><br><br>
        Role :
        <input type="radio" value="admin" name="role" required>Admin
        <input type="radio" value="player" name="role" required>Player
        <br><br>
        <input type="submit" name="submit" value="login">   
        <a href="/mini_pro/view/auth/registration.php">register</a>

    </form>

  
</body>
</html>