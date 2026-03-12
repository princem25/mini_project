<?php

class Leaderboard
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


    // Get leaderboard

    public function getLeaderboard($tour_id, $limit = null, $offset = null)
    {
        try {

            $sql = "
        SELECT 
            t.team_id,
            t.team_name,

            COUNT(m.match_id) AS matches_played,

            SUM(CASE 
                WHEN ms.winner_team_id = t.team_id THEN 1
                ELSE 0
            END) AS wins,

            SUM(CASE 
                WHEN ms.winner_team_id IS NULL 
                AND m.status='Completed' THEN 1
                ELSE 0
            END) AS draws,

            SUM(CASE 
                WHEN ms.winner_team_id IS NOT NULL 
                AND ms.winner_team_id != t.team_id 
                THEN 1
                ELSE 0
            END) AS losses,

            SUM(CASE 
                WHEN ms.winner_team_id = t.team_id THEN 2
                WHEN ms.winner_team_id IS NULL 
                AND m.status='Completed' THEN 1
                ELSE 0
            END) AS total_points

        FROM teams t

        LEFT JOIN matches m 
            ON (t.team_id = m.team1_id OR t.team_id = m.team2_id)

        LEFT JOIN match_scores ms 
            ON m.match_id = ms.match_id

        WHERE t.tour_id = :tour_id

        GROUP BY t.team_id, t.team_name

        ORDER BY total_points DESC
        ";

            if ($limit !== null && $offset !== null) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tour_id', $tour_id, PDO::PARAM_INT);

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

    public function getKnockoutChampion($tour_id)
    {
        try {
            $sql = "
            SELECT 
                t.team_id,
                t.team_name,
                ms.winner_team_id
            FROM matches m
            INNER JOIN match_scores ms ON m.match_id = ms.match_id
            INNER JOIN teams t ON ms.winner_team_id = t.team_id
            WHERE m.tour_id = :tour_id 
              AND m.status = 'Completed'
              AND ms.winner_team_id IS NOT NULL
            ORDER BY m.time DESC, m.match_id DESC
            LIMIT 1
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tour_id', $tour_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e);
            return false;
        }
    }
}
