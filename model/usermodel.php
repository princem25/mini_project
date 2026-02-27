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
            return false;
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
            return null;
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
            return null;
        }
    }
}
?>