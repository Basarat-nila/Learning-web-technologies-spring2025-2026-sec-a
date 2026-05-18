<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once "../config/database.php";
require_once "../models/Post.php";

/* ✅ Auto-login from remember cookie */
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {

    require_once "../models/User.php";

    $database = new Database();
    $db = $database->getConnection();

    $stmt = $db->query("SELECT * FROM users WHERE remember_token IS NOT NULL");

    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($_COOKIE['remember_me'], $user['remember_token'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_verified'] = $user['is_verified'];

            break;
        }
    }
}

/* ✅ Load approved posts */
$database = new Database();
$db = $database->getConnection();
$postModel = new Post($db);
$latestPosts = $postModel->getLatestApproved(6);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Travel Guide | Home</title>
    <link rel="stylesheet" href="assets/css/register.css">
    <script src="assets/js/validation.js"></script>
</head>
<body>

<?php include "../views/partials/navbar.php"; ?>

<div style="max-width:1100px;margin:40px auto;">

<?php if (!isset($_SESSION['user_id'])): ?>

    <!-- ✅ NON-REGISTERED USERS -->
    <div style="background:white;padding:40px;border-radius:12px;text-align:center;">
        <h2>Welcome to Travel Guide 🌍</h2>
        <p>Explore the world’s most beautiful destinations.</p>
        <br>
        <a href="../views/auth/register.php" style="margin-right:20px;">Register</a>
        <a href="../views/auth/login.php">Login</a>
    </div>

<?php else: ?>

    <?php if ($_SESSION['is_verified'] == 0): ?>

        <!-- ✅ LOGGED IN BUT NOT VERIFIED -->
        <div style="background:#ffe0e0;padding:25px;border-radius:12px;color:#900;">
            <h3>⚠ Account Pending Approval</h3>
            <p>Your account is waiting for admin verification.</p>
            <p>You cannot access detailed features until verified.</p>
        </div>

    <?php else: ?>

        <!-- ✅ VERIFIED USERS -->
        <h2 style="margin-bottom:25px;">Latest Approved Destinations ✈</h2>

        <?php if (empty($latestPosts)): ?>

            <div style="background:white;padding:25px;border-radius:12px;">
                No approved posts available yet.
            </div>

        <?php else: ?>

            <div style="display:grid;
                        grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
                        gap:25px;">

                <?php foreach ($latestPosts as $post): ?>

    <div style="background:white;
                padding:20px;
                border-radius:12px;
                box-shadow:0 6px 18px rgba(0,0,0,0.1);
                transition:0.3s;">

        <h3><?= htmlspecialchars($post['title']) ?></h3>

        <p><strong>Country:</strong> <?= htmlspecialchars($post['country']) ?></p>
        <p><strong>Genre:</strong> <?= htmlspecialchars($post['genre']) ?></p>
        <p><strong>Cost Level:</strong> <?= htmlspecialchars($post['cost_level']) ?></p>

        <p>
            <?= substr(htmlspecialchars($post['short_history']), 0, 120) ?>...
        </p>

        <?php if ($_SESSION['role'] == 'user'): ?>
            <button onclick="addToWishlist(<?= $post['id'] ?>, this)">
                ❤️ Add to Wishlist
            </button>
        <?php endif; ?>

    </div>

<?php endforeach; ?>

            </div>

            <div style="margin-top:40px;text-align:center;">
                <a href="#" style="font-weight:bold;font-size:18px;">
                    Browse All Destinations →
                </a>
            </div>

        <?php endif; ?>

    <?php endif; ?>

<?php endif; ?>

</div>

</body>
</html>