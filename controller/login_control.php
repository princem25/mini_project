    <?php
    header('Content-Type: application/json');

    $username = trim($_POST['name'] ?? '');
    $password = trim($_POST['pass'] ?? '');
    $role     = $_POST['role'] ?? '';

    require_once "C:/xampp_new/htdocs/mini_pro/config/dbconfig.php";
    require_once "C:/xampp_new/htdocs/mini_pro/model/usermodel.php";

    try {

        if ($connected != 1) {
            echo json_encode([
                "status" => "failed",
                "message" => "Database connection failed"
            ]);
            exit;
        }

        $userModel = new User($conn);
        $user = $userModel->login($username, $password, $role);

        if ($user) {

            session_start();
            session_regenerate_id(true);

            $_SESSION['role'] = (int)$role;
            setcookie("name",$username,time()+86400,"/");

            echo json_encode([
                "status" => "success",
                "role"   => $_SESSION['role'],
                 
            ]);
            exit;
        } else {
            echo json_encode([
                "status" => "failed",
                "message" => "Invalid credentials"
            ]);
        }
    } catch (PDOException $e) {

        file_put_contents(
            __DIR__ . "/../error.txt",
            date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL,
            FILE_APPEND
        );

        echo json_encode([
            "status" => "error",
            "message" => "Server error"
        ]);
    } finally {
        $conn = null;
    }
