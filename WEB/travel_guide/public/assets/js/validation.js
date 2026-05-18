function validateRegister() {

    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let terms = document.getElementById("terms").checked;

    if (name === "") {
        alert("Full name is required");
        return false;
    }

    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.match(emailPattern)) {
        alert("Please enter a valid email address");
        return false;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

    if (!terms) {
        alert("You must agree to Terms & Conditions");
        return false;
    }

    return true;
}


 function addToWishlist(postId, button) {

    fetch('/travel_guide/public/api/wishlist/add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {

            button.innerText = "✅ Added";
            button.disabled = true;
            button.style.background = "#28a745";

        } else {
            alert(data.message);
        }
    });
}

function validateProfile() {

    let name = document.querySelector("input[name='name']").value.trim();
    let email = document.querySelector("input[name='email']").value.trim();
    let newPassword = document.querySelector("input[name='new_password']").value;
    let confirmPassword = document.querySelector("input[name='confirm_new_password']").value;

    if (name === "") {
        alert("Name cannot be empty");
        return false;
    }

    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.match(emailPattern)) {
        alert("Invalid email format");
        return false;
    }

    if (newPassword !== "") {
        if (newPassword.length < 8) {
            alert("New password must be at least 8 characters");
            return false;
        }

        if (newPassword !== confirmPassword) {
            alert("New passwords do not match");
            return false;
        }
    }

    return true;
}
