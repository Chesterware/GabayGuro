<?php
require_once '../../db_connection.php';
require_once 'auth_admin.php';

if (isset($_POST['tutor_id'], $_POST['status'])) {
    $tutorId = (int) $_POST['tutor_id'];
    $status = $_POST['status'];

    $allowedStatuses = ['For Verification', 'Verified', 'Unverified'];
    if (!in_array($status, $allowedStatuses)) {
        $_SESSION['error'] = "Invalid status selected.";
        header("Location: ../AdminHTML/manage_users.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE tutor SET status = ? WHERE tutor_id = ?");
    $stmt->bind_param("si", $status, $tutorId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Tutor status updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update tutor status.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Missing required data.";
}

header("Location: ../AdminHTML/manage_users.php");
exit();
?>