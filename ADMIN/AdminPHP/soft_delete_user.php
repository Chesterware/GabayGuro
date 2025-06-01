<?php
require_once '../../db_connection.php';

if (!isset($_POST['user_type']) || !isset($_POST['user_id'])) {
    $_SESSION['admin_errors'] = ["Missing user information for archiving."];
    header("Location: ../AdminHTML/manage_users.php");
    exit;
}

$user_type = $_POST['user_type'];
$user_id = $_POST['user_id'];

if (!is_numeric($user_id)) {
    $_SESSION['admin_errors'] = ["Invalid user ID."];
    header("Location: ../AdminHTML/manage_users.php");
    exit;
}

if ($user_type === "tutor") {
    $stmt = $conn->prepare("UPDATE tutor SET is_deleted = 1, updated_at = NOW() WHERE tutor_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['admin_success'] = "Tutor has been archived successfully.";
    } else {
        $_SESSION['admin_errors'] = ["Failed to archive tutor: " . $conn->error];
    }
    $stmt->close();
} 
elseif ($user_type === "learner") {
    $stmt = $conn->prepare("UPDATE learner SET is_deleted = 1, updated_at = NOW() WHERE learner_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['admin_success'] = "Learner has been archived successfully.";
    } else {
        $_SESSION['admin_errors'] = ["Failed to archive learner: " . $conn->error];
    }
    $stmt->close();
} 
else {
    $_SESSION['admin_errors'] = ["Invalid user type specified."];
}

header("Location: ../AdminHTML/manage_users.php");
exit;
?>