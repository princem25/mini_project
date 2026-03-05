<?php

class Tournament
{
    private $conn;

    public function __construct($conn)
    {

        $this->conn = $conn;
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

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    public function checkTour($tourName)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT tour_id FROM tournaments WHERE tour_name = ?"
            );

            $stmt->execute([$tourName]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }


    public function deleteTour($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM tournaments WHERE tour_id = ?"
            );

            $stmt->execute([$id]);
            return $stmt->rowCount();   // number of rows deleted

        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    public function getTour()
    {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM tournaments  ORDER BY start_date ASC"
            );

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    public function updateTour($id, $name, $start, $end, $type, $status)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                "DELETE FROM tournaments WHERE tour_id = ?"
            );
            $stmt->execute([$id]);

            $stmt = $this->conn->prepare(
                "INSERT INTO tournaments
                 (tour_id, tour_name, start_date, end_date, type, status)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$id, $name, $start, $end, $type, $status]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }


    public function checkTourid($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT tour_id FROM tournaments WHERE tour_id = ?"
            );

            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
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

            return $stmt->rowCount();   // rows affected

        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    
 
}
