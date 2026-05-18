<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav style="background:#0077b6;padding:15px;color:white;display:flex;justify-content:space-between;align-items:center;">

    <div>
        <a href="/travel_guide/public/index.php" style="color:white;text-decoration:none;font-weight:bold;">
            🌍 Travel Guide
        </a>
    </div>

    <div>

        <?php if (!isset($_SESSION['user_id'])): ?>

            <a href="/travel_guide/views/auth/login.php" style="color:white;margin-right:15px;">Login</a>
            <a href="/travel_guide/views/auth/register.php" style="color:white;">Register</a>

        <?php else: ?>

            <a href="/travel_guide/public/index.php" style="color:white;margin-right:15px;">Home</a>

            <?php if ($_SESSION['is_verified'] == 1): ?>

                <a href="/travel_guide/views/profile.php" style="color:white;margin-right:15px;">Profile</a>

                <?php if ($_SESSION['role'] == 'user'): ?>
                    <a href="#" style="color:white;margin-right:15px;">Wishlist</a>
                <?php endif; ?>

                <?php if ($_SESSION['role'] == 'scout'): ?>
                    <a href="#" style="color:white;margin-right:15px;">Scout Panel</a>
                <?php endif; ?>

                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="#" style="color:white;margin-right:15px;">Admin Dashboard</a>
                <?php endif; ?>

            <?php endif; ?>

            <a href="/travel_guide/controllers/LogoutController.php" style="color:white;">Logout</a>

        <?php endif; ?>

    </div>

</nav>