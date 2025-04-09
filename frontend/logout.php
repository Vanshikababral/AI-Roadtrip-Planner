<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Include path config
include 'includes/header.php'; // This sets the $ROOT variable

// Redirect to the index page using the correct path
header("Location: $ROOT/index.php?logout=success");
exit();
?>
