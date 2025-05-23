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

    if ($userType === 'tutor') {
        $stmt = $conn->prepare("DELETE FROM tutor_specializations WHERE tutor_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("DELETE FROM $userType WHERE {$userType}_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = ucfirst($userType) . " deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete " . $userType . ".";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Missing required data.";
}

header("Location: ../AdminHTML/manage_users.php");
exit();
?>
