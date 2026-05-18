<?php

class PostRequest {

    private $conn;
    private $table = "post_requests";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByScout($scoutId) {
        $query = "SELECT * FROM post_requests 
                  WHERE scout_id = :scout_id
                  ORDER BY requested_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":scout_id", $scoutId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM post_requests WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $short_history, $country, $genre, $cost_level, $travel_medium_info) {

        $query = "UPDATE post_requests
                  SET title = :title,
                      short_history = :short_history,
                      country = :country,
                      genre = :genre,
                      cost_level = :cost_level,
                      travel_medium_info = :travel_medium_info
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':title' => $title,
            ':short_history' => $short_history,
            ':country' => $country,
            ':genre' => $genre,
            ':cost_level' => $cost_level,
            ':travel_medium_info' => $travel_medium_info,
            ':id' => $id
        ]);
    }

    public function delete($id, $scoutId) {
        $query = "DELETE FROM post_requests 
                  WHERE id = :id AND scout_id = :scout_id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id,
            ':scout_id' => $scoutId
        ]);
    }

    public function getApprovedPosts($scoutId) {
        $query = "SELECT * FROM posts 
                  WHERE scout_id = :scout_id 
                  AND status = 'approved'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":scout_id", $scoutId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}