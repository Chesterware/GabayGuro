<?php
session_start();
require_once 'api/db_connection.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: /iskol4rx/ADMIN/AdminPHP/manageUser.php");
    exit();
} elseif (isset($_SESSION['tutor_id'])) {
    header("Location: /iskol4rx/tutor/TutorHTML/ratings_review.php");
    exit();
} elseif (isset($_SESSION['learner_id'])) {
    header("Location: /iskol4rx/learner/LearnerHTML/notifications.php");
    exit();
}

$error_message = isset($_GET['error']) ? "Invalid email or password!" : "";
$logout_message = isset($_GET['logout']) && $_GET['logout'] === 'success' ? "You have been successfully logged out." : "";
?>

<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#003153">
<link rel="icon" href="/GabayGuroLogo.png">

<script>
    if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js')
        .then(() => console.log('Service Worker registered!'))
        .catch(err => console.error('Service Worker registration failed:', err));
    }
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabay Guro - Login</title>
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=Inter:wght@400&family=Raleway:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/iskol4rx/styles/login.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo"></div>
            <h1 class="welcome-text">Welcome to GABAYGURO</h1>
        </div>
    </header>
    
    <main class="login-container">
        <h2 class="login-title">Please log in to continue</h2>

        <!--
        <?php
            $password = "Learnerluise1";
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            echo $hashedPassword;
        ?>
        -->

        <?php if (!empty($logout_message)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($logout_message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="api/login.php">
            <div class="form-group">
                <input type="email" class="form-input" id="email" name="email" placeholder=" " required>
                <label class="form-label">Email address</label>
            </div>
            
            <div class="form-group">
                <input type="password" class="form-input" id="password" name="password" placeholder=" " required>
                <label class="form-label">Password</label>
                <div class="password-toggle" id="togglePassword"></div>
            </div>

            <div class="forgot-password">
                <a href="forgotPassword.php">Forgot your password?</a>
            </div>

            <button type="submit" class="login-btn">LOG IN</button>

            <div class="register-link">
                Don't have an account? <a href="register.php">REGISTER</a>
            </div>
        </form>
    </main>
</body>
</html>
