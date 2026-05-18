<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function register($name, $email, $password, $role) {
        $query = "INSERT INTO " . $this->table . "
                  (name, email, password_hash, role, is_verified, created_at)
                  VALUES (:name, :email, :password, :role, 0, NOW())";

        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();
    }
    public function findByEmail($email) {
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateRememberToken($userId, $token) {
    $query = "UPDATE users SET remember_token = :token WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":token", $token);
    $stmt->bindParam(":id", $userId);
    return $stmt->execute();
}



public function findById($id) {
    $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateProfile($id, $name, $email, $profilePicture) {

    if ($profilePicture) {
        $query = "UPDATE users 
                  SET name = :name, email = :email, profile_picture = :pic 
                  WHERE id = :id";
    } else {
        $query = "UPDATE users 
                  SET name = :name, email = :email 
                  WHERE id = :id";
    }

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":id", $id);

    if ($profilePicture) {
        $stmt->bindParam(":pic", $profilePicture);
    }

    return $stmt->execute();
}

public function updatePassword($id, $newPassword) {
    $query = "UPDATE users SET password_hash = :password WHERE id = :id";
    $stmt = $this->conn->prepare($query);

    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt->bindParam(":password", $hashed);
    $stmt->bindParam(":id", $id);

    return $stmt->execute();
}
}