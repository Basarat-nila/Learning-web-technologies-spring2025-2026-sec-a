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

$database = new Database();
$db = $database->getConnection();
$postModel = new Post($db);

$latestPosts = $postModel->getLatestApproved();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Travel Guide | Home</title>
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>

<?php include "../views/partials/navbar.php"; ?>

<div style="max-width:1000px;margin:40px auto;">

<?php if (!isset($_SESSION['user_id'])): ?>

    <!-- ✅ Guest View -->
    <div style="background:white;padding:40px;border-radius:12px;text-align:center;">
        <h2>Welcome to Travel Guide 🌍</h2>
        <p>Discover amazing destinations around the world.</p>
        <br>
        <a href="../views/auth/register.php">Register</a> |
        <a href="../views/auth/login.php">Login</a>
    </div>

<?php else: ?>

    <?php if ($_SESSION['is_verified'] == 0): ?>

        <!-- ✅ Not Verified -->
        <div style="background:#ffdddd;padding:20px;border-radius:10px;color:#900;">
            ⚠ Your account is pending admin approval.
            You cannot access detailed site features until verified.
        </div>

    <?php else: ?>

        <!-- ✅ Verified User View -->
        <h2 style="margin-bottom:20px;">Latest Destinations ✈</h2>

        <?php if (empty($latestPosts)): ?>

            <div style="background:white;padding:20px;border-radius:10px;">
                No approved posts available yet.
            </div>

        <?php else: ?>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px;">

                <?php foreach ($latestPosts as $post): ?>

                    <div style="background:white;padding:20px;border-radius:12px;box-shadow:0 5px 15px rgba(0,0,0,0.1);">

                        <h3><?= htmlspecialchars($post['title']) ?></h3>

                        <p><strong>Country:</strong> <?= htmlspecialchars($post['country']) ?></p>
                        <p><strong>Genre:</strong> <?= htmlspecialchars($post['genre']) ?></p>
                        <p><strong>Cost Level:</strong> <?= htmlspecialchars($post['cost_level']) ?></p>

                        <p>
                            <?= substr(htmlspecialchars($post['short_history']), 0, 100) ?>...
                        </p>

                    </div>

                <?php endforeach; ?>

            </div>

            <br><br>
            <a href="#" style="font-weight:bold;">Browse All Destinations →</a>

        <?php endif; ?>

    <?php endif; ?>

<?php endif; ?>

</div>

</body>
</html>