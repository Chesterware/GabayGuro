<?php
require_once '../../db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../index.php");
    exit();
}

$success_msg = null;
$error_msg = null;

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_msg = isset($_GET['msg']) ? urldecode($_GET['msg']) : "Operation completed successfully";
} else if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error_msg = isset($_GET['msg']) ? urldecode($_GET['msg']) : "An error occurred";
}

$admin_id = $_SESSION['admin_id'];
$sql = "SELECT * FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $first_name = trim($_POST['first_name']);
        $middle_initial = trim($_POST['middle_initial']);
        $last_name = trim($_POST['last_name']);
        
        if (empty($first_name) || empty($last_name)) {
            $redirect_url = "admin_profile.php?error=1&msg=" . urlencode("First name and last name are required");
            header("Location: " . $redirect_url);
            exit();
        } else {
            $update_sql = "UPDATE admin SET first_name = ?, middle_initial = ?, last_name = ?, updated_at = NOW() WHERE admin_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssi", $first_name, $middle_initial, $last_name, $admin_id);
            
            if ($update_stmt->execute()) {
                $redirect_url = "admin_profile.php?success=1&msg=" . urlencode("Profile updated successfully");
                $update_stmt->close();
                header("Location: " . $redirect_url);
                exit();
            } else {
                $redirect_url = "admin_profile.php?error=1&msg=" . urlencode("Error updating profile: " . $conn->error);
                $update_stmt->close();
                header("Location: " . $redirect_url);
                exit();
            }
        }
    }
    
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $redirect_url = "admin_profile.php?error=1&msg=" . urlencode("All password fields are required");
            header("Location: " . $redirect_url);
            exit();
        } elseif ($new_password !== $confirm_password) {
            $redirect_url = "admin_profile.php?error=1&msg=" . urlencode("New passwords do not match");
            header("Location: " . $redirect_url);
            exit();
        } elseif (!password_verify($current_password, $admin['password'])) {
            $redirect_url = "admin_profile.php?error=1&msg=" . urlencode("Current password is incorrect");
            header("Location: " . $redirect_url);
            exit();
        } else {
            $password_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/";
            if (!preg_match($password_regex, $new_password)) {
                $redirect_url = "admin_profile.php?error=1&msg=" . urlencode("Password must be at least 8 characters and include at least 1 uppercase letter, 1 lowercase letter, and 1 number.");
                header("Location: " . $redirect_url);
                exit();
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE admin SET password = ?, updated_at = NOW() WHERE admin_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $hashed_password, $admin_id);
                
                if ($update_stmt->execute()) {
                    $redirect_url = "admin_profile.php?success=1&msg=" . urlencode("Password updated successfully");
                    $update_stmt->close();
                    header("Location: " . $redirect_url);
                    exit();
                } else {
                    $redirect_url = "admin_profile.php?error=1&msg=" . urlencode("Error updating password: " . $conn->error);
                    $update_stmt->close();
                    header("Location: " . $redirect_url);
                    exit();
                }
            }
        }
    }
}
?>