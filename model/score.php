<?php

class Scores
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

    public function matchscoreReg($matchid, $team1, $team2, $winner)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO match_scores (match_id,team1_score,team2_score,winner_team_id)
                 VALUES (?,?,?,?)"
            );
            $stmt->execute([$matchid, $team1, $team2, $winner]);
            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function matchscoreupdate($matchid, $team1, $team2, $winner)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE match_scores
             SET team1_score = ?, team2_score = ?, winner_team_id = ?
             WHERE match_id = ?"
            );

            $stmt->execute([$team1, $team2, $winner, $matchid]);

            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getMatchscore()
    {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM match_scores"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }
}
