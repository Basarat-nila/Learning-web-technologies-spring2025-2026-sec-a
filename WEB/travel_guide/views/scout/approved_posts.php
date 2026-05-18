<?php
session_start();

require_once "../../config/database.php";
require_once "../../models/PostRequest.php";

$database = new Database();
$db = $database->getConnection();
$model = new PostRequest($db);

$approved = $model->getApprovedPosts($_SESSION['user_id']);
?>

<h2>My Approved Posts</h2>

<?php foreach ($approved as $post): ?>

    <div style="border:1px solid #ccc;padding:15px;margin-bottom:10px;">

        <h3><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= htmlspecialchars($post['country']) ?></p>

        <button onclick="requestChange(<?= $post['id'] ?>)">
            Request Change
        </button>

    </div>

<?php endforeach; ?>

<script>
function requestChange(postId) {

    fetch('../../public/api/scout/request_change.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ original_post_id: postId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            alert("Change request submitted!");
        }
    });
}
</script>