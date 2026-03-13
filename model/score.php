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

    public function matchscoreReg($matchid, $team1, $team2, $winner,$status)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO match_scores (match_id,team1_score,team2_score,winner_team_id)
                 VALUES (?,?,?,?)"
            );
            $stmt->execute([$matchid, $team1, $team2, $winner]);

             $stmt = $this->conn->prepare(
                "UPDATE matches SET status = ? WHERE match_id = ?"
            );
            $stmt->execute([$status, $matchid]);
           
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

            $stmtStatus = $this->conn->prepare(
                "UPDATE matches SET status = 'Completed' WHERE match_id = ?"
            );
            $stmtStatus->execute([$matchid]);

            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getMatchscore($limit = null, $offset = null)
    {
        try {
            $sql = "SELECT * FROM match_scores";
            if ($limit !== null && $offset !== null) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }
            $stmt = $this->conn->prepare($sql);
            if ($limit !== null && $offset !== null) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

        public function checkmatchscore($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM match_scores where match_id = ?"
            );
            $stmt->execute([$id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function deletescore($matchid)
    {
        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM match_scores WHERE match_id = ?"
            );
            $stmt->execute([$matchid]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }
}

