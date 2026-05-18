<?php
session_start();

if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'scout' ||
    $_SESSION['is_verified'] != 1) {

    header("Location: ../../public/index.php");
    exit();
}

require_once "../../config/database.php";
require_once "../../models/PostRequest.php";

$database = new Database();
$db = $database->getConnection();
$model = new PostRequest($db);

$requests = $model->getByScout($_SESSION['user_id']);
?>

<h2>My Post Requests</h2>

<?php foreach ($requests as $req): ?>

    <div style="border:1px solid #ccc;padding:15px;margin-bottom:10px;">

        <h3><?= htmlspecialchars($req['title']) ?></h3>
        <p>Status: <strong><?= $req['status'] ?></strong></p>

        <?php if ($req['status'] == 'pending'): ?>

            <a href="edit_request.php?id=<?= $req['id'] ?>">Edit</a>
            <button onclick="deleteRequest(<?= $req['id'] ?>)">Delete</button>

        <?php endif; ?>

    </div>

<?php endforeach; ?>

<script>
function deleteRequest(id) {

    if (!confirm("Delete this request?")) return;

    fetch('../../public/api/scout/delete_request.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            location.reload();
        }
    });
}
</script>