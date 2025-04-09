<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$ROOT = "/ai-roadtrip-planner/frontend";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AI Road Trip Planner</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?= $ROOT ?>/style.css">
  <link rel="stylesheet" href="<?= $ROOT ?>/chatbot/chatbot.css">
  <link rel="stylesheet" href="<?= $ROOT ?>/index.css">
  <link rel="stylesheet" href="<?= $ROOT ?>/includes/footer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>

  <style>

.nav-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(90deg,rgb(147, 207, 187),rgb(152, 189, 175)); /* Matches the body color */
    color: #fff;
    padding: 10px 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    z-index: 100;
}

    .nav-container {
      max-width: 1200px;
      margin: auto;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
    }

    .nav-logo {
      font-size: 35px;
      font-weight: bold;
      white-space: nowrap;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    .nav-links a {
      color: #fff;
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 8px;
      transition: background-color 0.3s ease;
      font-weight: 500;
    }

    .nav-links a:hover,
    .nav-links a.active {
      background-color: rgba(255, 255, 255, 0.15);
    }

    .nav-user {
      color: #f3f4f6;
      font-weight: 500;
      white-space: nowrap;
    }

    .nav-logout {
      background-color:rgb(170, 214, 197);
    }

    .nav-logout:hover {
      background-color: #dc2626;
    }

    @media (max-width: 768px) {
      .nav-container {
        flex-direction: column;
        align-items: flex-start;
      }

      .nav-links {
        width: 100%;
        margin-top: 10px;
        flex-direction: column;
        align-items: flex-start;
      }

      .nav-links a, .nav-user {
        font-size: 15px;
        padding: 6px 10px;
      }
    }
  </style>
</head>


  <nav class="nav-wrapper">
    <div class="nav-container">
      <div class="nav-logo">AI Road Trip Planner</div>
      <div class="nav-links">
        <a href="<?= $ROOT ?>/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a>
        <a href="<?= $ROOT ?>/contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact Us</a>

        <?php if (isset($_SESSION['user_id'])): ?>
          <span class="nav-user">👤 <?= htmlspecialchars($_SESSION['fullname']) ?></span>
          <a href="<?= $ROOT ?>/logout.php" class="nav-logout">Logout</a>
        <?php else: ?>
          <a href="<?= $ROOT ?>/login.php" class="<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">Login</a>
          <a href="<?= $ROOT ?>/signup.php" class="<?= basename($_SERVER['PHP_SELF']) == 'signup.php' ? 'active' : '' ?>">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
