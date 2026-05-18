<?php
session_start();

require_once "../config/database.php";
require_once "../models/User.php";

class AuthController {

    public function register() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role = $_POST['role'];

            $errors = [];

            // Server-side validation
            if (empty($name)) $errors[] = "Name is required";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
            if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";

            $allowed_roles = ['admin','scout','user'];
            if (!in_array($role, $allowed_roles)) {
                $errors[] = "Invalid role selected";
            }

            $database = new Database();
            $db = $database->getConnection();
            $user = new User($db);

            if ($user->emailExists($email)) {
                $errors[] = "Email already exists";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: ../views/auth/register.php");
                exit();
            }

            if ($user->register($name, $email, $password, $role)) {
                $_SESSION['success'] = "Registration successful! Wait for admin approval.";
                header("Location: ../views/auth/login.php");
                exit();
            }
        }
    }
}

$controller = new AuthController();
$controller->register();