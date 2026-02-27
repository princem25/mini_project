    <?php
    header('Content-Type: application/json');

    $id = trim($_POST['id'] ?? '');
     
    require_once "C:/xampp_new/htdocs/mini_pro/config/dbconfig.php";
    require_once "C:/xampp_new/htdocs/mini_pro/model/tournament.php";

    try {
  
        if ($connected != 1) {
            echo json_encode([
                "status" => "failed",
                "message" => "Database connection failed"
            ]);
            exit;
        }

        $tourModel = new Tournament($conn);
        $tour = $tourModel->checkTourid($id);

        if (!$tour){
             echo json_encode([
                "status" => "failed",
                "message"   =>"tournament not exists",
                 
            ]);
            exit;
        } 

        $newTour = $tourModel->deleteTour($id);

        if($newTour){
            echo json_encode([
                "status" => "success",
                "message"   =>"tournament deleted successfully",
                 
            ]);
             exit;
        }
   
         else {
            echo json_encode([
                "status" => "failed",
                "message" => "Invalid details not deleted"
            ]);
        }
  
   
    } catch (PDOException $e) {

        file_put_contents(
            __DIR__ . "/../error.txt",
            date("H:i:s Y-m-d : ") . $e->getMessage().$e->getFile().$e->getLine() . PHP_EOL,
            FILE_APPEND
        );

        echo json_encode([
            "status" => "error",
            "message" => "Server error"
        ]);
    } finally {
        $conn = null;
    }
    

  