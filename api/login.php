<?php
session_start();
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit();
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];

$user_types = ['admin', 'tutor', 'learner'];

foreach ($user_types as $user_type) {
    $table = $user_type;
    $id_field = $user_type . '_id';
    
    if ($user_type == 'tutor' || $user_type == 'learner') {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ? AND (is_deleted = 0 OR is_deleted IS NULL)");
    } else {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            if (($user_type == 'tutor' || $user_type == 'learner') && 
                isset($user['is_deleted']) && $user['is_deleted'] == 1) {
                header("Location: /iskol4rx/index.php?error=account_deactivated");
                exit();
            }
            
            session_regenerate_id(true);
            
            $_SESSION[$id_field] = $user[$id_field];
            $_SESSION['user_type'] = $user_type;
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            if (isset($_SESSION['admin_id'])) {
                header("Location: /iskol4rx/ADMIN/AdminHTML/admin_dashboard.php");
            } elseif (isset($_SESSION['tutor_id'])) {
                header("Location: /iskol4rx/tutor/TutorHTML/ratings_review.php");
            } elseif (isset($_SESSION['learner_id'])) {
                header("Location: /iskol4rx/learner/LearnerHTML/notifications.php");
            }

            exit();
        } else {
            header("Location: /iskol4rx/index.php?error=invalid");
            exit();
        }
    }
    $stmt->close();
    
    if ($user_type == 'tutor' || $user_type == 'learner') {
        $deleted_check = $conn->prepare("SELECT * FROM $table WHERE email = ? AND is_deleted = 1");
        $deleted_check->bind_param("s", $email);
        $deleted_check->execute();
        $deleted_result = $deleted_check->get_result();
        
        if ($deleted_result->num_rows === 1) {
            header("Location: /iskol4rx/index.php?error=account_deactivated");
            exit();
        }
        $deleted_check->close();
    }
}

header("Location: /iskol4rx/index.php?error=invalid");
exit();
?>