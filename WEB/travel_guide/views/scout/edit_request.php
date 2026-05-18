<form onsubmit="updateRequest(event)">

<input type="hidden" id="req_id" value="<?= $request['id'] ?>">

<input type="text" id="title" value="<?= htmlspecialchars($request['title']) ?>"><br><br>

<textarea id="short_history"><?= htmlspecialchars($request['short_history']) ?></textarea><br><br>

<input type="text" id="country" value="<?= htmlspecialchars($request['country']) ?>"><br><br>

<button type="submit">Update</button>

</form>

<script>
function updateRequest(e) {

    e.preventDefault();

    fetch('../../public/api/scout/update_request.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: document.getElementById("req_id").value,
            title: document.getElementById("title").value,
            short_history: document.getElementById("short_history").value,
            country: document.getElementById("country").value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            alert("Updated!");
        }
    });
}
</script>