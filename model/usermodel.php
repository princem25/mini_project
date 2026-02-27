<?php

class User
{
    private $conn;

    public function __construct($conn)
    {

        $this->conn = $conn;
    }

    // REGISTER USER
    public function register($username, $pass, $roleid)
    {
        try {
            $hash = hash("sha256", $pass);

            $stmt = $this->conn->prepare(
                "INSERT INTO users (name, password, role_id)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([$username, $hash, $roleid]);

            return true;
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    // CHECK IF USER EXISTS
    public function check($username, $roleid)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT user_id FROM users WHERE name = ? AND role_id = ?"
            );

            $stmt->execute([$username, $roleid]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }

    // LOGIN USER
    public function login($username, $pass, $roleid)
    {
        try {
            $hash = hash("sha256", $pass);

            $stmt = $this->conn->prepare(
                "SELECT role_id FROM users
                 WHERE name = ? AND password = ? AND role_id = ?"
            );

            $stmt->execute([$username, $hash, $roleid]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {

            file_put_contents(__DIR__ . "/../error.txt", date("H:i:s Y-m-d : ") . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "error : " . $e->getMessage();
        }
    }
}
