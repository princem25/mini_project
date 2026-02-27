<?php

class Tournament
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // REGISTER tour
    public function registerTour($tourName, $startDate, $endDate, $type, $status)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO tournaments (tour_name,start_date,end_date,type,status)
             VALUES (?, ?, ?,?,?)"
        );

        return $stmt->execute([$tourName, $startDate, $endDate, $type, $status]);
    }

    // CHECK IF tour EXISTS
    public function checkTour($tourName)
    {
        $stmt = $this->conn->prepare(
            "SELECT tour_id FROM tournaments WHERE tour_name = ?"
        );
        $stmt->execute([$tourName]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function deleteTour($id)
    {

        $stmt = $this->conn->prepare(
            "delete from tournaments 
             WHERE tour_id = ?"
        );



        return  $stmt->execute([$id]);
    }

    public function getTour()
    {
        $stmt = $this->conn->query(
            "SELECT * FROM tournaments order by start_date asc"
        );
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }


    public function updateTour($id, $name, $start, $end, $type, $status)
    {
        try {
            // start transaction
            $this->conn->beginTransaction();

            // delete existing record
            $stmt = $this->conn->prepare(
                "DELETE FROM tournaments WHERE tour_id = ?"
            );
            $stmt->execute([$id]);

            // insert updated record
            $stmt = $this->conn->prepare(
                "INSERT INTO tournaments 
            (tour_id, tour_name, start_date, end_date, type, status)
            VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->execute([$id, $name, $start, $end, $type, $status]);

            // commit if all successful
            $this->conn->commit();

            return true;
        } catch (PDOException $e) {

            // rollback if any error occurs
            $this->conn->rollBack();

            return false; // or return $e->getMessage() for debugging
        }
    }

    public function checkTourid($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT tour_id FROM tournaments WHERE tour_id = ?"
        );
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function statuschange($id, $status)
    {
        $stmt = $this->conn->prepare(
            "update tournaments 
            set status = ?
            where tour_id = $id"
        );

        return  $stmt->execute([$status]);
    }
}
