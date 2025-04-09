<?php
session_start();
require_once __DIR__ . '/../database/db_config.php';
 // Adjust path if db_connect.php is in root

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        header("Location: contact.php?error=empty");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.php?error=invalid_email");
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$name, $email, $subject, $message])) {
            // Optional email notification
            $to = "info@airoadtrip.com"; // Change to your admin email
            $headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();
            mail($to, "New Message: $subject", $message, $headers);

            header("Location: contact.php?success=true");
            exit();
        } else {
            throw new PDOException("Database insertion failed.");
        }
    } catch (PDOException $e) {
        error_log("Contact Form Error: " . $e->getMessage());
        header("Location: contact.php?error=server");
        exit();
    }
}
?>
