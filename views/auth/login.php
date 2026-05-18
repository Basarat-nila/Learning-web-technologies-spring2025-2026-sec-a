<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Travel Guide | Login</title>
    <link rel="stylesheet" href="../../public/assets/css/register.css">
</head>
<body>

<h1 class="sticky-header">🌍 Travel Guide Login</h1>

<form action="../../controllers/LoginController.php" method="POST">

    <?php
    if(isset($_SESSION['errors'])) {
        foreach($_SESSION['errors'] as $error) {
            echo "<div class='error-message'>$error</div>";
        }
        unset($_SESSION['errors']);
    }

    if(isset($_SESSION['success'])) {
        echo "<div class='success-message'>".$_SESSION['success']."</div>";
        unset($_SESSION['success']);
    }
    ?>

    <fieldset>
        <legend>Login Details</legend>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <div class="checkbox-group">
            <input type="checkbox" name="remember_me" id="remember_me">
            <label for="remember_me">Remember Me (30 days)</label>
        </div>
    </fieldset>

    <button type="submit">Login</button>

</form>

</body>
</html>