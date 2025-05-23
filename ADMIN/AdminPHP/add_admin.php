<?php
require_once '../../db_connection.php';
require_once 'auth_admin.php';

if (isset($_POST['add_admin'])) {
    $first_name = trim($_POST['first_name']);
    $middle_initial = trim($_POST['middle_initial']) ?: null;
    $last_name = trim($_POST['last_name']);
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors[] = 'Password must be at least 8 characters and include uppercase, lowercase, and number.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $errors[] = 'Email already registered.';
        }
    }

    if (!empty($errors)) {
        session_start();
        $_SESSION['admin_errors'] = $errors;
        header("Location: ../AdminHTML/manage_users.php");
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admin (first_name, middle_initial, last_name, email, password, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'Inactive', NOW(), NOW())");
    $stmt->bind_param("sssss", $first_name, $middle_initial, $last_name, $email, $password_hash);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['admin_success'] = 'Admin account created successfully.';
        header("Location: ../AdminHTML/manage_users.php");
        exit;
    } else {
        session_start();
        $_SESSION['admin_errors'] = ['Error creating admin account.'];
        header("Location: ../AdminHTML/manage_users.php");
        exit;
    }
}
?>