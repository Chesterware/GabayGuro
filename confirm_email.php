<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Email Confirmation</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003153">
    <link rel="icon" href="/GabayGuroLogo.png">
    <link rel="icon" href="GabayGuroLogo.png" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=Inter:wght@400;500&family=Raleway:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/iskol4rx/styles/register.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="GabayGuroLogo.png" alt="Gabay Guro Logo">
        </div>
        <h1 class="title">Welcome to GABAYGURO</h1>
    </div>

    <div class="registration-container">
        <div class="registration-box">
            <?php
                $errorMsg = $_GET['error'] ?? '';
                $successMsg = $_GET['success'] ?? '';
                if ($errorMsg):
            ?>
                <div class="error-message"><?= htmlspecialchars($errorMsg) ?></div>
            <?php elseif ($successMsg): ?>
                <div class="success-message"><?= htmlspecialchars($successMsg) ?></div>
            <?php endif; ?>

            <form method="POST" action="api/send_confirmation.php" class="registration-form">
                <div class="form-group">
                    <label for="role">Select Role</label>
                    <select id="role" name="role" required>
                        <option value="learner" selected>LEARNER</option>
                        <option value="tutor">TUTOR</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required />
                </div>

                <button type="submit" class="submit-btn">CONTINUE</button>

                <div class="login-link">
                    <label>Already have an account?</label>
                    <a href="index.php">LOGIN</a>
                </div>

            </form>
        </div>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(() => console.log('Service Worker registered!'))
                .catch(err => console.error('Service Worker registration failed:', err));
        }
    </script>
</body>
</html>
