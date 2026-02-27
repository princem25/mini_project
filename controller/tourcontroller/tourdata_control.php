    <?php
    header('Content-Type: application/json');
  
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
        $tour = $tourModel->getTour();

        if ($tour){
             echo json_encode([
                "status" => "success",
                "message" =>"tournament fetched",
                "data"=>$tour
                 
            ]);
            exit;
        } 
 
            echo json_encode([
                "status" => "failed",
                "message" => "data not fetched"
            ]);
        }
    
     catch (PDOException $e) {

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
    

  