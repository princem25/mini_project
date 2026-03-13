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

    public function registerMatch($tourid, $team1, $team2, $date, $status)
    {
        try {
            // Ensure canonical order: smaller team_id first
            $minTeam = min($team1, $team2);
            $maxTeam = max($team1, $team2);
            $stmt = $this->conn->prepare(
                "INSERT INTO matches (tour_id,team1_id,team2_id,time,status)
                 VALUES (?,?,?,?,?)"
            );
            $stmt->execute([$tourid, $minTeam, $maxTeam, $date, $status]);
            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkMatch($team1, $team2)
    {
        try {
            // Always check in canonical order (smaller id first)
            $minTeam = min($team1, $team2);
            $maxTeam = max($team1, $team2);
            $stmt = $this->conn->prepare(
                "SELECT match_id FROM matches WHERE team1_id = ? and team2_id = ?"
            );
            $stmt->execute([$minTeam, $maxTeam]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getMatches($limit = null, $offset = null, $search = null)
    {
        try {
            $sql = "SELECT * FROM matches";
            if ($search !== null && $search !== "") {
                $sql .= " WHERE status LIKE :search OR time LIKE :search";
            }
            if ($limit !== null && $offset !== null) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }
            $stmt = $this->conn->prepare($sql);
            
            if ($search !== null && $search !== "") {
                $searchTerm = '%' . $search . '%';
                $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            }
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

    public function getMatch($id)
    {
        try {

            $stmt = $this->conn->prepare(
                "SELECT * FROM matches WHERE match_id = ?"
            );

            $stmt->execute([$id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function checkTour($tourid, $team1, $team2)
    {

        try {
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) as total 
             FROM teams 
             WHERE tour_id = ? AND team_id IN (?,?)"
            );

            $stmt->execute([$tourid, $team1, $team2]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['total'] == 2;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function updatematch($id, $status)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE matches SET status = ? WHERE match_id = ?"
            );
            $stmt->execute([$status, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getmatchdate()
    {
        try {
            $stmt = $this->conn->query(
                "select time from matches"
            );
           
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkTeamAvailabilityOnDate($team1, $team2, $date)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT match_id FROM matches 
                 WHERE (team1_id IN (?, ?) OR team2_id IN (?, ?))
                 AND DATE(time) = DATE(?)"
            );
            $stmt->execute([$team1, $team2, $team1, $team2, $date]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function deleteMatch($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM matches WHERE match_id = ?"
            );
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    
}

