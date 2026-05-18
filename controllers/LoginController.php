<?php
session_start();

require_once "../config/database.php";
require_once "../models/User.php";

class LoginController {

    public function login() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $remember = isset($_POST['remember_me']);

            $errors = [];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }

            if (empty($password)) {
                $errors[] = "Password is required";
            }

            $database = new Database();
            $db = $database->getConnection();
            $userModel = new User($db);

            $user = $userModel->findByEmail($email);

            if (!$user) {
                $errors[] = "User not found";
            } else {
                if (!password_verify($password, $user['password_hash'])) {
                    $errors[] = "Incorrect password";
                }
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: ../views/auth/login.php");
                exit();
            }

            // ✅ Create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_verified'] = $user['is_verified'];
            //verification status is stored in session

            // ✅ Remember Me
            if ($remember) {

                $token = bin2hex(random_bytes(32)); // secure token
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);

                $userModel->updateRememberToken($user['id'], $hashedToken);

                setcookie(
                    "remember_me",
                    $token,
                    time() + (30 * 24 * 60 * 60),
                    "/",
                    "",
                    false,
                    true
                );
            }

            header("Location: ../public/index.php");
            exit();
        }
    }
}

$controller = new LoginController();
$controller->login();