<?php

class Post {

    private $conn;
    private $table = "posts";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ✅ Get latest approved posts
    public function getLatestApproved($limit = 6) {

        $query = "SELECT * FROM " . $this->table . " 
                  WHERE status = 'approved'
                  ORDER BY created_at DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}