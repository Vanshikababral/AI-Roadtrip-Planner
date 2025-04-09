<?php
session_start();
require_once 'db_config.php'; // ✅ Load DB connection here

// Input validation
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Basic validation
if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
    header("Location: ../signup.php?error=empty");
    exit();
}

if (strlen($password) < 6) {
    header("Location: ../signup.php?error=password_length");
    exit();
}

if ($password !== $confirm_password) {
    header("Location: ../signup.php?error=password_mismatch");
    exit();
}

// Check if email already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    header("Location: ../signup.php?error=email_exists");
    exit();
}

// Insert new user
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$insert = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
$insert->execute([$fullname, $email, $hashed_password]);

// Set session and redirect to homepage
$_SESSION['user_id'] = $conn->lastInsertId();
$_SESSION['fullname'] = $fullname;

header("Location: ../index.php");
exit();
?>
