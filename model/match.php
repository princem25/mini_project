<?php

class Matches
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    private function logError($e)
    {
        file_put_contents(
            __DIR__ . "/../error.txt",
            date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL,
            FILE_APPEND
        );
    }

    public function registerMatch($tourid, $team1, $team2, $date)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO matches (tour_id,team1_id,team2_id,time)
                 VALUES (?,?,?,?)"
            );
            $stmt->execute([$tourid, $team1, $team2, $date]);
            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkMatch($team1, $team2)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT match_id FROM matches WHERE team1_id = ? and team2_id = ?
                union
                SELECT match_id FROM matches WHERE team1_id = ? and team2_id = ?
                "
            );
            $stmt->execute([$team1, $team2, $team2, $team1]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getMatches()
    {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM matches"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkDate($tourid, $time)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM tournaments 
     WHERE tour_id = ? 
     AND ? BETWEEN DATE(start_date) AND DATE(end_date)"
            );
            $stmt->execute([$tourid, $time]);
      
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

   public function checkTour($tourid,$team1,$team2)
{
     
    try {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as total 
             FROM teams 
             WHERE tour_id = ? AND team_id IN (?,?)"
        );

        $stmt->execute([$tourid,$team1,$team2]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'] == 2;

    } catch (PDOException $e) {
        $this->logError($e);
        return false;
    }
}
}
