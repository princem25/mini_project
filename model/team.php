<?php

class Team
{
    private $conn;

    public function __construct($conn)
    {

        $this->conn = $conn;
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

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
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

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }


    public function deleteTeam($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM teams WHERE team_id = ?"
            );

            $stmt->execute([$id]);
            return $stmt->rowCount();   // number of rows deleted

        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    public function getTeams()
    {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM teams"
            );

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    public function updateTeam($id, $name)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                "DELETE FROM teams WHERE team_id = ?"
            );
            $stmt->execute([$id]);

            $stmt = $this->conn->prepare(
                "INSERT INTO teams
                 (team_id, team_name)
                 VALUES (?, ?)"
            );
            $stmt->execute([$id, $name]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }


    public function checkTeamid($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT tour_id FROM teams WHERE team_id = ?"
            );

            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    
    
}
