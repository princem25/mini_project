<?php

class Player
{
    private $conn;

    public function __construct($conn)
    {

        $this->conn = $conn;
    }

    public function getPlayerTeam(){
        try {
            $stmt = $this->conn->query(
                "SELECT u.user_id,u.name,team_id FROM users as u
                left join players as p
                on u.user_id = p.user_id
                where u.role_id = 2 and p.team_id is null"
            );

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

     public function playertoteam($teamid,$userid){
            try {
             $stmt = $this->conn->prepare("
             insert into players(team_id,user_id) values (?,?)
        ");

        $stmt->execute([$teamid, $userid]);

        return true;

    } catch (PDOException $e) {
        file_put_contents(
            __DIR__ . "/../error.txt",
            date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL,
            FILE_APPEND
        );

    
    }


     
}
}