<?php
require_once 'api/db_connection.php';
require_once 'api/forgotPassword.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Gabay Guro Administrator</title>
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/iskol4rx/styles/forgotPassword.css">
</head>
<body>
    <div class="header-container">
        <img src="images/Gabay Guro - Logo.png" alt="Gabay Guro Logo" class="logo-img">
        <h1>Forgot Password</h1>
    </div>

    <div class="container-fluid d-flex justify-content-center">
        <div class="col-md-12 login-column">
            <div class="login-container">
                <h3>Reset Your Password</h3>
                <p>Enter your email address and we'll send you a link to reset your password.</p>
                
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div><?= htmlspecialchars($error_message); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div><?= htmlspecialchars($success_message); ?></div>
                    </div>
                <?php endif; ?>

                <form class="login-form" method="POST">
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            <label for="email">Email Address</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary login-btn">SEND RESET LINK</button>
                    
                    <div class="text-center mt-3">
                        <a href="login.php" class="forgot-password-link">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>