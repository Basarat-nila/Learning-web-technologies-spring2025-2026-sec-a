<?php
session_start();

require_once "../config/database.php";
require_once "../models/PostRequest.php";

$database = new Database();
$db = $database->getConnection();
$model = new PostRequest($db);

$model->update(
    $_POST['id'],
    $_POST['title'],
    $_POST['short_history'],
    $_POST['country'],
    $_POST['genre'] ?? 'city',
    $_POST['cost_level'] ?? 'medium',
    $_POST['travel_medium_info'] ?? ''
);

header("Location: ../views/scout/my_requests.php");
exit();