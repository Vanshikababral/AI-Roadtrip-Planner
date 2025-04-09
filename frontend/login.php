
<?php
$ROOT = "/ai-roadtrip-planner/frontend"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - AI Road Trip Planner</title>
    <link rel="stylesheet" href="<?= $ROOT ?>/style.css"> 
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:rgb(211, 225, 220);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .form-box h2 {
            margin-bottom: 1rem;
            color: #3b82f6;
        }

        .form-box p {
            margin-bottom: 1.5rem;
            color: #374151;
        }

        .form-box input {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .toggle-eye {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 14px;
            color: #6b7280;
        }

        .form-box button {
            width: 100%;
            padding: 10px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-box button:hover {
            background-color: #2563eb;
        }

        .form-box .text {
            margin-top: 1rem;
            font-size: 14px;
            color: #6b7280;
        }

        .form-box .text a {
            color: #3b82f6;
            text-decoration: none;
        }

        .form-box .text a:hover {
            text-decoration: underline;
        }

        .error {
            background-color: #fef2f2;
            color: #b91c1c;
            padding: 10px;
            border: 1px solid #fca5a5;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .success {
            background-color: #ecfdf5;
            color: #065f46;
            padding: 10px;
            border: 1px solid #6ee7b7;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="<?= $ROOT ?>/../database/validate_login.php" method="POST" class="form-box">
            <h2>Welcome Back</h2>
            <p>Please login to continue</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="error">
                    <?php 
                        switch($_GET['error']) {
                            case 'invalid':
                                echo "Invalid email or password";
                                break;
                            case 'server':
                                echo "Server error occurred";
                                break;
                            case 'empty':
                                echo "Please fill in all fields";
                                break;
                            default:
                                echo "An error occurred";
                        }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="success">Successfully registered! Please login.</div>
            <?php endif; ?>

            <input type="email" name="email" placeholder="Email" required>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span onclick="togglePassword()" class="toggle-eye">👁️</span>
            </div>
            <button type="submit">Login</button>
            <p class="text">Don't have an account? <a href="<?= $ROOT ?>/signup.php">Sign up</a></p>
        </form>
    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            pass.type = pass.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>