<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Travel Guide | Register</title>
    <link rel="stylesheet" href="../../public/assets/css/register.css">
    <script src="../../public/assets/js/validation.js"></script>
</head>
<body>

<h1 class="sticky-header">🌍 Travel Guide Registration</h1>

<form action="../../controllers/AuthController.php" method="POST" onsubmit="return validateRegister()">

    <?php
    if(isset($_SESSION['errors'])) {
        foreach($_SESSION['errors'] as $error) {
            echo "<div class='error-message'>$error</div>";
        }
        unset($_SESSION['errors']);
    }
    ?>

    <fieldset>
        <legend>Basic Information</legend>

        <label>Full Name</label>
        <input type="text" name="name" id="name" placeholder="Enter your full name">

        <label>Email Address</label>
        <input type="email" name="email" id="email" placeholder="Enter your email">

        <label>Password</label>
        <input type="password" name="password" id="password" placeholder="Minimum 8 characters">

        <label>Confirm Password</label>
        <input type="password" id="confirmPassword" placeholder="Re-enter password">
    </fieldset>

    <fieldset>
        <legend>Account Type</legend>

        <label>Select Role</label>
        <select name="role">
            <option value="user">General User</option>
            <option value="scout">Scout</option>
            <option value="admin">Admin</option>
        </select>
    </fieldset>

    <fieldset>
        <legend>Agreements</legend>

        <input type="checkbox" id="terms">
        <label for="terms">I agree to Terms & Conditions</label>
    </fieldset>

    <button type="submit">Create Account</button>

</form>

</body>
</html>