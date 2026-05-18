<?php
session_start();

/* ✅ Must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

/* ✅ Block non-verified users */
if (!isset($_SESSION['is_verified']) || $_SESSION['is_verified'] == 0) {
    $_SESSION['errors'] = ["Your account is pending admin approval."];
    header("Location: ../public/index.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/User.php";

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

$user = $userModel->findById($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../public/assets/css/register.css">
</head>
<body>

<?php include "partials/navbar.php"; ?>

<div style="max-width:700px;margin:40px auto;background:white;padding:30px;border-radius:12px;box-shadow:0 8px 20px rgba(0,0,0,0.15);">

<h2 style="text-align:center;margin-bottom:20px;">👤 My Profile</h2>

<?php
/* ✅ Success message */
if(isset($_SESSION['success'])) {
    echo "<div class='success-message'>".$_SESSION['success']."</div>";
    unset($_SESSION['success']);
}

/* ✅ Error messages */
if(isset($_SESSION['errors'])) {
    foreach($_SESSION['errors'] as $error) {
        echo "<div class='error-message'>$error</div>";
    }
    unset($_SESSION['errors']);
}
?>

<form action="../controllers/ProfileController.php" method="POST" enctype="multipart/form-data">

    <fieldset>
        <legend>Basic Information</legend>

        <label>Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Profile Picture</label>
        <input type="file" name="profile_picture">

        <?php if (!empty($user['profile_picture'])): ?>
            <div style="margin-top:15px;">
                <p>Current Profile Picture:</p>
                <img src="../public/uploads/<?= htmlspecialchars($user['profile_picture']) ?>" 
                     width="120" 
                     style="border-radius:8px;">
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset>
        <legend>Change Password</legend>

        <label>Current Password</label>
        <input type="password" name="current_password">

        <label>New Password</label>
        <input type="password" name="new_password">

        <label>Confirm New Password</label>
        <input type="password" name="confirm_new_password">
    </fieldset>

    <button type="submit">Update Profile</button>

</form>

</div>

</body>
</html>