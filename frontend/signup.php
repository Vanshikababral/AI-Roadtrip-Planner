<?php
session_start();
include 'includes/header.php';
?>
<link rel="stylesheet" href="<?= $ROOT ?>/style.css">

<div class="container">
    <form action="<?= $ROOT ?>/../database/insert_user.php" method="POST" class="form-box" onsubmit="return validateForm()">
        <h2>Create Account</h2>
        <p>Sign up to get started on your next scenic adventure 🚗</p>

        <!-- Error Message Box -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <?php
                    switch ($_GET['error']) {
                        case 'password_mismatch':
                            echo "🚫 Passwords do not match!";
                            break;
                        case 'email_exists':
                            echo "🚫 Email already registered.";
                            break;
                        case 'password_length':
                            echo "🚫 Password must be at least 6 characters.";
                            break;
                        case 'empty':
                            echo "🚫 Please fill in all fields.";
                            break;
                        case 'server':
                            echo "⚠️ Server error. Please try again.";
                            break;
                        default:
                            echo "❌ Something went wrong.";
                    }
                ?>
            </div>
        <?php endif; ?>

        <!-- Form Fields -->
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>

        <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span onclick="togglePassword('password')" class="toggle-eye">👁️</span>
        </div>

        <div class="password-wrapper">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            <span onclick="togglePassword('confirm_password')" class="toggle-eye">👁️</span>
        </div>

        <label class="checkbox">
            <input type="checkbox" required> I agree to the Terms & Conditions
        </label>

        <button type="submit">Create Account</button>
        <p class="text">Already have an account? <a href="<?= $ROOT ?>/login.php">Sign in</a></p>
    </form>
</div>

<!-- JS: Show/Hide Password & Confirm Match -->
<script>
    function togglePassword(id) {
        const field = document.getElementById(id);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    function validateForm() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;
        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return false;
        }
        if (password.length < 6) {
            alert("Password must be at least 6 characters.");
            return false;
        }
        return true;
    }
</script>

