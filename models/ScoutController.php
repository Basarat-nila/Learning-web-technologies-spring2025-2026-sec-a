<?php
session_start();

require_once "../config/database.php";

if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'scout' ||
    $_SESSION['is_verified'] != 1) {

    header("Location: ../public/index.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

/* ✅ Get form data */
$title = trim($_POST['title']);
$short_history = trim($_POST['short_history']);
$country = trim($_POST['country']);
$genre = $_POST['genre'];
$cost_level = $_POST['cost_level'];
$travel_medium_info = trim($_POST['travel_medium_info']);

/* ✅ PHP VALIDATION */
if (empty($title) || empty($short_history) || empty($country)) {
    die("All required fields must be filled.");
}

if (strlen($short_history) < 10) {
    die("Short history must be at least 10 characters.");
}

/* ✅ Image Upload */
$imageName = null;

if (!empty($_FILES['image']['name'])) {

    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    if (!in_array($_FILES['image']['type'], $allowedTypes)) {
        die("Only JPG and PNG images allowed.");
    }

    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
        die("Image size must be less than 2MB.");
    }

    $imageName = time() . "_" . $_FILES['image']['name'];

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        "../public/uploads/posts/" . $imageName
    );
}

/* ✅ Insert into post_requests */
$query = "INSERT INTO post_requests 
          (scout_id, title, short_history, country, genre, cost_level, travel_medium_info, image)
          VALUES 
          (:scout_id, :title, :short_history, :country, :genre, :cost_level, :travel_medium_info, :image)";

$stmt = $db->prepare($query);

$stmt->execute([
    ':scout_id' => $_SESSION['user_id'],
    ':title' => $title,
    ':short_history' => $short_history,
    ':country' => $country,
    ':genre' => $genre,
    ':cost_level' => $cost_level,
    ':travel_medium_info' => $travel_medium_info,
    ':image' => $imageName
]);

header("Location: ../views/scout/my_requests.php");
exit();