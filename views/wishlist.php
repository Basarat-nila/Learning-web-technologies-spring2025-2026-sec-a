<?php
session_start();

/*  Allow only verified general users */
if (!isset($_SESSION['user_id']) || 
    $_SESSION['role'] !== 'user' || 
    $_SESSION['is_verified'] != 1) {

    header("Location: ../public/index.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/Wishlist.php";

$database = new Database();
$db = $database->getConnection();
$wishlistModel = new Wishlist($db);

$items = $wishlistModel->getUserWishlist($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist</title>
    <link rel="stylesheet" href="../public/assets/css/register.css">
</head>
<body>

<?php include "partials/navbar.php"; ?>

<div style="max-width:800px;margin:40px auto;">

<h2 style="margin-bottom:20px;">❤️ My Wishlist</h2>

<?php if (empty($items)): ?>
    <div style="background:white;padding:20px;border-radius:10px;">
        No items in wishlist yet.
    </div>
<?php else: ?>

    <?php foreach ($items as $item): ?>

        <div style="background:white;
                    padding:20px;
                    margin-bottom:15px;
                    border-radius:10px;
                    box-shadow:0 4px 10px rgba(0,0,0,0.1);">

            <h3><?= htmlspecialchars($item['title']) ?></h3>

            <p>
                <strong>Country:</strong> <?= htmlspecialchars($item['country']) ?> |
                <strong>Cost:</strong> <?= htmlspecialchars($item['cost_level']) ?>
            </p>

            <button onclick="removeFromWishlist(<?= $item['id'] ?>, this)">
                Remove
            </button>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

</div>

<script>
function removeFromWishlist(postId, button) {

    fetch('/travel_guide/public/api/wishlist/remove.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {

            //  Remove card instantly (no reload)
            button.closest("div").remove();

        } else {
            alert("Error removing item.");
        }
    });
}
</script>

</body>
</html>