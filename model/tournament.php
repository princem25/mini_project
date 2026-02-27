<?php

class Tournament
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // REGISTER tour
    public function registerTour($tourName, $startDate,$endDate,$type,$status)
    { 
        $stmt = $this->conn->prepare(
            "INSERT INTO tournaments (tour_name,start_date,end_date,type,status)
             VALUES (?, ?, ?,?,?)"
        );

        return $stmt->execute([$tourName, $startDate, $endDate,$type,$status]);
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

 
    public function deleteTour($tourName)
    {
      
        $stmt = $this->conn->prepare(
            "delete from tournaments 
             WHERE tour_name = ?"
        );

        $stmt->execute([$tourName]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>