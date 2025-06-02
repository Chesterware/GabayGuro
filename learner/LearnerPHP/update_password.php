<?php
session_start();
require_once '../../db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['learner_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$learner_id = $_SESSION['learner_id'];

if (empty($_POST['current_password']) || empty($_POST['new_password'])) {
    echo json_encode(['success' => false, 'message' => 'All password fields are required']);
    exit();
}

$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];

if (strlen($new_password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
    exit();
}

if (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number']);
    exit();
}

try {
    $sql = "SELECT password FROM learner WHERE learner_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $learner_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }
    
    $user = $result->fetch_assoc();
    
    if (!password_verify($current_password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit();
    }
    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $update_sql = "UPDATE learner SET password = ? WHERE learner_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $hashed_password, $learner_id);
    
    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update password: ' . $update_stmt->error]);
    }
    
    $update_stmt->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>