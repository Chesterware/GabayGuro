<?php
require_once '../../db_connection.php';

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['tutor_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$response = ['success' => false, 'message' => ''];

try {
    $tutor_id = $_SESSION['tutor_id'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (empty($current_password)) {
        throw new Exception('Current password is required');
    }

    if (empty($new_password)) {
        throw new Exception('New password is required');
    }

    if (strlen($new_password) < 8) {
        throw new Exception('Password must be at least 8 characters long');
    }
    
    if (!preg_match('/[A-Z]/', $new_password) || 
        !preg_match('/[a-z]/', $new_password) || 
        !preg_match('/[0-9]/', $new_password)) {
        throw new Exception('Password must contain at least one uppercase letter, one lowercase letter, and one number');
    }

    // Verify current password
    $get_password_sql = "SELECT password FROM tutor WHERE tutor_id = ?";
    $get_password_stmt = $conn->prepare($get_password_sql);
    if (!$get_password_stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }

    $get_password_stmt->bind_param("i", $tutor_id);
    $get_password_stmt->execute();
    $get_password_result = $get_password_stmt->get_result();

    if ($get_password_result->num_rows !== 1) {
        throw new Exception('Tutor not found');
    }

    $tutor_data = $get_password_result->fetch_assoc();
    $current_hashed_password = $tutor_data['password'];

    if (!password_verify($current_password, $current_hashed_password)) {
        throw new Exception('Current password is incorrect');
    }

    $get_password_stmt->close();

    // Update the password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_sql = "UPDATE tutor SET 
            password = ?,
            updated_at = NOW()
            WHERE tutor_id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }

    $update_stmt->bind_param("si", $hashed_password, $tutor_id);

    if (!$update_stmt->execute()) {
        throw new Exception('Database update failed: ' . $update_stmt->error);
    }

    $response['success'] = true;
    $response['message'] = 'Password updated successfully';
    
    $update_stmt->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Password update error: " . $e->getMessage());
}

if (ob_get_length()) {
    ob_clean();
}

echo json_encode($response);
exit();
?>