<?php
session_start();
header('Content-Type: application/json');

require_once "../../../config/database.php";
require_once "../../../models/Wishlist.php";

if (!isset($_SESSION['user_id']) || 
    $_SESSION['role'] !== 'user' || 
    $_SESSION['is_verified'] != 1) {

    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$userId = $_SESSION['user_id'];
$postId = $data['post_id'] ?? null;

$database = new Database();
$db = $database->getConnection();
$wishlist = new Wishlist($db);

if ($wishlist->remove($userId, $postId)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}