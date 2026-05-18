<?php
session_start();
header('Content-Type: application/json');

require_once "../../../config/database.php";

if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'scout') {

    echo json_encode(["status" => "error"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$database = new Database();
$db = $database->getConnection();

$query = "INSERT INTO post_requests 
          (scout_id, title, short_history, country, genre, cost_level, travel_medium_info, original_post_id)
          SELECT scout_id, title, short_history, country, genre, cost_level, travel_medium_info, :original_post_id
          FROM posts WHERE id = :original_post_id";

$stmt = $db->prepare($query);

$stmt->execute([
    ':original_post_id' => $data['original_post_id']
]);

echo json_encode(["status" => "success"]);