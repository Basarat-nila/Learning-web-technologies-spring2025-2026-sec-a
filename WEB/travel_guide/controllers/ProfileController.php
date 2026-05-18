<?php
session_start();

if ($_SESSION['is_verified'] == 0) {
    header("Location: ../public/index.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/auth/login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/User.php";

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

$id = $_SESSION['user_id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);

$errors = [];

/* ✅ Validate name & email */
if (empty($name)) {
    $errors[] = "Name cannot be empty";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email";
}

/* ✅ Handle file upload */
$profilePictureName = null;

if (!empty($_FILES['profile_picture']['name'])) {

    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    if (!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
        $errors[] = "Only JPG and PNG allowed";
    }

    if ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
        $errors[] = "Max file size 2MB";
    }

    if (empty($errors)) {
        $profilePictureName = time() . "_" . $_FILES['profile_picture']['name'];
        move_uploaded_file(
            $_FILES['profile_picture']['tmp_name'],
            "../public/uploads/" . $profilePictureName
        );
    }
}

/* ✅ Change password */
$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];
$confirmNewPassword = $_POST['confirm_new_password'];

if (!empty($newPassword)) {

    $user = $userModel->findById($id);

    if (!password_verify($currentPassword, $user['password_hash'])) {
        $errors[] = "Current password incorrect";
    }

    if (strlen($newPassword) < 8) {
        $errors[] = "New password must be at least 8 characters";
    }

    if ($newPassword !== $confirmNewPassword) {
        $errors[] = "New passwords do not match";
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../views/profile.php");
    exit();
}

/* ✅ Update profile */
$userModel->updateProfile($id, $name, $email, $profilePictureName);

/* ✅ Update password if provided */
if (!empty($newPassword)) {
    $userModel->updatePassword($id, $newPassword);
}

$_SESSION['success'] = "Profile updated successfully!";
header("Location: ../views/profile.php");
exit();