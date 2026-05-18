<?php
session_start();

if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'scout' ||
    $_SESSION['is_verified'] != 1) {

    header("Location: ../../public/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post Request</title>
</head>
<body>

<h2>Create Post Request</h2>

<form action="../../controllers/ScoutController.php" 
      method="POST" 
      enctype="multipart/form-data"
      onsubmit="return validateScoutForm()">

    <input type="text" name="title" placeholder="Title" required><br><br>

    <textarea name="short_history" placeholder="Short History" required></textarea><br><br>

    <input type="text" name="country" placeholder="Country" required><br><br>

    <select name="genre" required>
        <option value="">Select Genre</option>
        <option value="beach">Beach</option>
        <option value="mountain">Mountain</option>
        <option value="city">City</option>
        <option value="historical">Historical</option>
    </select><br><br>

    <select name="cost_level" required>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select><br><br>

    <textarea name="travel_medium_info" placeholder="Travel Info"></textarea><br><br>

    <input type="file" name="image"><br><br>

    <button type="submit">Submit Request</button>

</form>

<script>
function validateScoutForm() {

    let title = document.querySelector("input[name='title']").value.trim();
    let history = document.querySelector("textarea[name='short_history']").value.trim();
    let country = document.querySelector("input[name='country']").value.trim();

    if (title === "") {
        alert("Title is required");
        return false;
    }

    if (history.length < 10) {
        alert("Short history must be at least 10 characters");
        return false;
    }

    if (country === "") {
        alert("Country is required");
        return false;
    }

    return true;
}
</script>

</body>
</html>