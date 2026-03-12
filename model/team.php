<?php

class Team
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

    public function registerTeam($name)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO teams (team_name)
                 VALUES (?)"
            );
            $stmt->execute([$name]);
            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkTeam($name)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT team_id FROM teams WHERE team_name = ?"
            );
            $stmt->execute([$name]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function deleteTeam($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM teams WHERE team_id = ?"
            );
            $stmt->execute([$id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getTeams($limit = null, $offset = null)
    {
        try {
            $sql = "SELECT team_id,team_name,COALESCE(tour_id, 'not part of any tournament') as tour_id,verified FROM teams";
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

    public function updateTeam($id, $name)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE teams SET team_name = ? WHERE team_id = ?"
            );
            $stmt->execute([$name, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkTeamid($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT team_id FROM teams WHERE team_id = ?"
            );
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function teamWithoutTour()
    {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM teams where tour_id is null and verified = 1"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

       public function teamWithTour()
    {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM teams where verified = 1"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function teamAssignTour($teamid, $tourid)
    {
        try {
            $stmt = $this->conn->prepare("
                UPDATE teams 
                SET tour_id = ?
                WHERE team_id = ?
            ");
            $stmt->execute([$tourid, $teamid]);
            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }
}