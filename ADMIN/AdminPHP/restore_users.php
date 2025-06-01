<?php
require_once '../../db_connection.php';
require_once 'auth_admin.php';

if (isset($_POST['user_type'], $_POST['user_id'])) {
    $userType = $_POST['user_type'];
    $userId = (int) $_POST['user_id'];

    $allowedTypes = ['tutor', 'learner'];
    if (!in_array($userType, $allowedTypes)) {
        die('Invalid user type.');
    }

    $stmt = $conn->prepare("UPDATE $userType SET is_deleted = 0 WHERE {$userType}_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['admin_success'] = ucfirst($userType) . " restored successfully.";
    } else {
        $_SESSION['admin_errors'][] = "Failed to restore " . $userType . ".";
    }

    $stmt->close();
} else {
    $_SESSION['admin_errors'][] = "Missing required data.";
}

header("Location: ../AdminHTML/manage_users.php");
exit();
?>