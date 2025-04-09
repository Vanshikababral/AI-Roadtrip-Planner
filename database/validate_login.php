<?php
session_start();
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["fullname"] = $user["fullname"];
            header("Location: ../frontend/index.php"); // ✅ redirect to correct folder
        } else {
            header("Location: ../frontend/login.php?error=invalid"); // ✅ redirect on failure
        }
        exit;
    } catch (PDOException $e) {
        // Optional: log error somewhere
        header("Location: ../frontend/login.php?error=server");
        exit;
    }
}
?>
