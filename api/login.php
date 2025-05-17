<?php
session_start();

require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /iskol4rx/login.php");
    exit();
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];

$user_types = ['admin', 'tutor', 'learner'];

foreach ($user_types as $user_type) {
    $table = $user_type;
    $id_field = $user_type . '_id';
    
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            
            $_SESSION[$id_field] = $user[$id_field];
            $_SESSION['user_type'] = $user_type;
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            if (isset($_SESSION['admin_id'])) {
                header("Location: /iskol4rx/ADMIN/AdminPHP/manageUser.php");
            } elseif (isset($_SESSION['tutor_id'])) {
                header("Location: /iskol4rx/tutor/TutorHTML/ratings_review.php");
            } elseif (isset($_SESSION['learner_id'])) {
                header("Location: /iskol4rx/learner/LearnerHTML/notifications.php");
            }

            exit();
        }
    }
    $stmt->close();
}

header("Location: /iskol4rx/login.php?error=invalid");
exit();
?>
