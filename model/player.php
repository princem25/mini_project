<?php

class Player
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

    public function getAllPlayers($limit = null, $offset = null)
    {
        try {
            $sql = "SELECT u.user_id,u.name,COALESCE(t.team_name, 'not part of any team') as team_id FROM users as u
                     left join players as p on u.user_id = p.user_id
                      left join teams as t on p.team_id = t.team_id 
                      where u.role_id = 2 and p.team_id is null 
                      union SELECT u.user_id as id,u.name
                       as nm,COALESCE(t.team_name, 'not part of any team') as tid FROM users as u
                        left join players as p on u.user_id = p.user_id 
                        left join teams as t on p.team_id = t.team_id where u.role_id = 2";
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

    public function getUnassignedPlayers()
    {
        try {
            $stmt = $this->conn->query("
            SELECT u.user_id, u.name
            FROM users AS u
            LEFT JOIN players AS p 
            ON u.user_id = p.user_id
            WHERE u.role_id = 2 
            AND p.user_id IS NULL
");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getAssignedPlayers()
    {
        try {
            $stmt = $this->conn->query(
                "SELECT player_id ,name, team_name FROM players as p
                join teams as t on p.team_id = t.team_id
                join users as u on p.user_id = u.user_id
                where p.team_id is not null
               "
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function assignToTeam($teamid, $userid)
    {
        try {
            $stmt = $this->conn->prepare("
             insert into players(team_id,user_id) values (?,?)
        ");
            $stmt->execute([$teamid, $userid]);
            return true;
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function removeFromTeam($id)
    {
        try {
            $stmt = $this->conn->prepare(
                "delete from players
                where player_id = ?"
            );
            $stmt->execute([$id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }

    public function getPlayerCountByTeam($teamid)
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM players WHERE team_id = ?");
            $stmt->execute([$teamid]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        } catch (PDOException $e) {
            $this->logError($e);
            return 0;
        }
    }
}

