<?php

class User
{
    private $conn;

    public function __construct($conn)
    {
        
        $this->conn = $conn;
    }

    // REGISTER USER
    public function register($email, $name, $pass, $roleid)
    {
        try {
            $hash = hash("sha256", $pass);

            $stmt = $this->conn->prepare(
                "INSERT INTO users (email, name, password, role_id)
                 VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([$email, $name, $hash, $roleid]);

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    // CHECK IF USER EXISTS
    public function check($email, $roleid)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT user_id FROM users WHERE email = ? AND role_id = ?"
            );

            $stmt->execute([$email, $roleid]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            return null;
        }
    }

    // LOGIN USER
    public function login($email, $pass, $roleid)
    {
        try {
            $hash = hash("sha256", $pass);

            $stmt = $this->conn->prepare(
                "SELECT role_id, name FROM users
                 WHERE email = ? AND password = ? AND role_id = ?"
            );

            $stmt->execute([$email, $hash, $roleid]);

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            return null;
        }
    }
}
?>