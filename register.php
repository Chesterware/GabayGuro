<?php
require_once 'api/register.php';



if ($step === 'role') {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role - GABAYGURO</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=Raleway:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/iskol4rx/styles/select_role.css">
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h1 class="title">Welcome to GABAYGURO</h1>
    </div>
    
    <div class="role-selection">
        <h2>Please select your role to continue</h2>
        
        <div class="role-buttons">
            <a href="register.php?role=learner&step=credentials" class="role-option">
                <div class="role-icon">📚</div>
                <div>LEARNER</div>
                <small>I want to find tutors</small>
            </a>
            <a href="register.php?role=tutor&step=credentials" class="role-option">
                <div class="role-icon">👩‍🏫</div>
                <div>TUTOR</div>
                <small>I want to teach students</small>
            </a>
        </div>
    </div>
</body>
</html>
<?php
exit();
}

if ($step === 'credentials') {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GABAYGURO</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=Inter:wght@400;500&family=Raleway:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/iskol4rx/styles/register.css">
    <style>
        
    </style>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h1 class="title">Welcome to GABAYGURO</h1>
    </div>
    
    <div class="registration-container">
        <div class="registration-box">
            <h2 class="registration-title">Register as <?= strtoupper($role) ?></h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="register.php?role=<?= $role ?>&step=credentials" class="registration-form">
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required minlength="8">
                    <div class="form-hint">Minimum 8 characters</div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="submit-btn">CONTINUE</button>
            </form>
            
            <a href="register.php" class="back-link">← Back to role selection</a>
        </div>
    </div>
</body>
</html>
<?php
exit();
}
?>