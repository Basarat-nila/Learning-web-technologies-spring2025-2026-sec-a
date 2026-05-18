<?php
session_start();

require_once "../config/database.php";
require_once "../models/User.php";

/* ✅ Remove remember token from database */
if (isset($_SESSION['user_id'])) {

    $database = new Database();
    $db = $database->getConnection();
    $userModel = new User($db);

    $userModel->updateRememberToken($_SESSION['user_id'], null);
}

/* ✅ Delete cookie */
setcookie("remember_me", "", time() - 3600, "/");

/* ✅ Destroy session */
session_unset();
session_destroy();

header("Location: ../views/auth/login.php");
exit();