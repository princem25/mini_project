<?php

class Tournament
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

    public function registerTour($tourName, $startDate, $endDate, $type, $status)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO tournaments (tour_name,start_date,end_date,type,status)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$tourName, $startDate, $endDate, $type, $status]);
            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkTour($tourName)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT tour_id FROM tournaments WHERE LOWER(tour_name) = LOWER(?)"
            );
            $stmt->execute([$tourName]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function deleteTour($id)
    {
        try {
            // First, set teams' tour_id to NULL
            $stmt1 = $this->conn->prepare(
                "UPDATE teams SET tour_id = NULL WHERE tour_id = ?"
            );
            $stmt1->execute([$id]);

            // Then delete the tournament
            $stmt2 = $this->conn->prepare(
                "DELETE FROM tournaments WHERE tour_id = ?"
            );
            $stmt2->execute([$id]);
            return $stmt2->rowCount();
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getTour($limit = null, $offset = null)
    {
        try {
            $sql = "SELECT * FROM tournaments ORDER BY start_date ASC";
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

    public function updateTour($id, $name, $start, $end, $type, $status)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE tournaments
                 SET tour_name = ?, start_date = ?, end_date = ?, type = ?, status = ?
                 WHERE tour_id = ?"
            );
            $stmt->execute([$name, $start, $end, $type, $status, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkTourid($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM tournaments WHERE tour_id = ?"
            );
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function statuschange($id, $status)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE tournaments
                 SET status = ?
                 WHERE tour_id = ?"
            );
            $stmt->execute([$status, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

     public function getTourverified()
    {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM tournaments where verified = 1"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }
      
    public function getTourById($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM tournaments WHERE tour_id = ?"
            );
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function checkAndCompleteTournament($tour_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM matches WHERE tour_id = ?");
            $stmt->execute([$tour_id]);
            $totalMatches = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            if ($totalMatches > 0) {
                $stmt2 = $this->conn->prepare("SELECT COUNT(*) as incomplete FROM matches WHERE tour_id = ? AND status != 'Completed'");
                $stmt2->execute([$tour_id]);
                $incomplete = $stmt2->fetch(PDO::FETCH_ASSOC)['incomplete'] ?? 0;

                if ($incomplete == 0) {
                    $stmt3 = $this->conn->prepare("UPDATE tournaments SET status = 'Completed' WHERE tour_id = ? AND status != 'Completed'");
                    $stmt3->execute([$tour_id]);
                    return true;
                }
            }
            return false;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }
}

