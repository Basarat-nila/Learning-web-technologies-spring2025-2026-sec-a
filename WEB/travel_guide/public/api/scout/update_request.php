<?php
session_start();
header('Content-Type: application/json');

require_once "../../../config/database.php";
require_once "../../../models/PostRequest.php";

$data = json_decode(file_get_contents("php://input"), true);

$database = new Database();
$db = $database->getConnection();
$model = new PostRequest($db);

$model->update(
    $data['id'],
    $data['title'],
    $data['short_history'],
    $data['country'],
    'city',
    'medium',
    ''
);

echo json_encode(["status" => "success"]);