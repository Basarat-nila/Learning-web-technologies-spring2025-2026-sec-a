<?php
session_start();
header('Content-Type: application/json');

require_once "../../../config/database.php";
require_once "../../../models/PostRequest.php";

if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'scout') {

    echo json_encode(["status" => "error"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$database = new Database();
$db = $database->getConnection();
$model = new PostRequest($db);

$model->delete($data['id'], $_SESSION['user_id']);

echo json_encode(["status" => "success"]);