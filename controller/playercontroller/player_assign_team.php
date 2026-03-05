    <?php
    header('Content-Type: application/json');

    $teamid = trim($_POST['teamid'] ?? '');
    $userid = trim($_POST['userid'] ?? '');

    require_once "../../config/dbconfig.php";
    require_once "../../model/player.php";
    try {

        if ($connected != 1) {
            echo json_encode([
                "status" => "failed",
                "message" => "Database connection failed"
            ]);
            exit;
        }

        $playerModel = new Player($conn);
        $player = $playerModel->playertoteam($teamid,$userid);

        if (!$player) {
            echo json_encode([
                "status" => "failed",
                "message"   => "player not updated",

            ]);
            exit;
        }

         
         else {
            echo json_encode([
                "status" => "success",
                "message" => "player updated"
            ]);

        
        }
    } catch (PDOException $e) {

        file_put_contents(
            __DIR__ . "/../error.txt",
            date("H:i:s Y-m-d : ") . $e->getMessage() . $e->getFile() . $e->getLine() . PHP_EOL,
            FILE_APPEND
        );

        echo json_encode([
            "status" => "error",
            "message" => "Server error"
        ]);
    } finally {
        $conn = null;
    }
