    <?php
    header('Content-Type: application/json');

    $teamname = trim($_POST['name'] ?? '');

    require_once "../../config/dbconfig.php";
    require_once "../../model/team.php";

    try {

        if ($connected != 1) {
            echo json_encode([
                "status" => "failed",
                "message" => "Database connection failed"
            ]);
            exit;
        }

        $teamModel = new Team($conn);
        $team = $teamModel->checkTeam($teamname);

        if ($team) {
            echo json_encode([
                "status" => "success",
                "message"   => "team already exists",

            ]);
            exit;
        }

        $newTeam = $teamModel->registerTeam($teamname);

        if ($newTeam) {
            echo json_encode([
                "status" => "success",
                "message"   => "team created successfully",

            ]);
            exit;
        } else {
            echo json_encode([
                "status" => "failed",
                "message" => "Invalid details"
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
