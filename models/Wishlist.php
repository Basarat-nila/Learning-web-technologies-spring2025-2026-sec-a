<?php
class Wishlist {

    private $conn;
    private $table = "wishlist";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($userId, $postId) {

        $query = "INSERT INTO wishlist (user_id, post_id) 
                  VALUES (:user_id, :post_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":post_id", $postId);

        return $stmt->execute();
    }

    public function remove($userId, $postId) {

        $query = "DELETE FROM wishlist 
                  WHERE user_id = :user_id AND post_id = :post_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":post_id", $postId);

        return $stmt->execute();
    }

    public function getUserWishlist($userId) {

        $query = "SELECT posts.id, posts.title, posts.country, posts.cost_level
                  FROM wishlist
                  JOIN posts ON wishlist.post_id = posts.id
                  WHERE wishlist.user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}