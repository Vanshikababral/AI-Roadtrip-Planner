<?php
session_start();
require_once __DIR__ . '/../database/db_config.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        header("Location: signup.php?error=empty#signup");
        exit();
    }

    // Password strength check
    if (strlen($password) < 6) {
        header("Location: signup.php?error=password_length#signup");
        exit();
    }

    // Password confirmation check
    if ($password !== $confirm_password) {
        header("Location: signup.php?error=password_mismatch#signup");
        exit();
    }

    try {
        // Check if the user already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            header("Location: signup.php?error=email_exists#signup");
            exit();
        }

        // Hash and insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");

        if ($insert_stmt->execute([$fullname, $email, $hashed_password])) {
            // Redirect to login with a success flag
            header("Location: login.php?success=registered");
            exit();
        } else {
            throw new PDOException("User registration failed.");
        }

    } catch (PDOException $e) {
        error_log("AI Road Trip Planner Signup Error: " . $e->getMessage());
        header("Location: signup.php?error=server#signup");
        exit();
    }
}
?>
