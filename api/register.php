<?php
require_once 'db_connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$role = isset($_GET['role']) ? $_GET['role'] : '';
$step = isset($_GET['step']) ? $_GET['step'] : 'role';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 'credentials') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    if (empty($email)) {
        $error = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (empty($password)) {
        $error = "Password is required!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords don't match!";
    } else {
        $stmt = $conn->prepare("SELECT email FROM tutor WHERE email = ? UNION SELECT email FROM learner WHERE email = ?");
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $_SESSION['register_email'] = $email;
            $_SESSION['register_password'] = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['register_role'] = $role;
            
            if ($role === 'tutor') {
                header("Location: register_tutor.php");
                exit();
            } else {
                header("Location: register_learner.php");
                exit();
            }
        }
    }
}
?>