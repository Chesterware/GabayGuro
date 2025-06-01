<?php
require_once '../../db_connection.php';

ob_start();

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
    
    error_log("Received POST data: " . print_r($_POST, true));
    
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_initial = trim($_POST['middle_initial'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $educational_attainment = $_POST['educational_attainment'] ?? '';
    $years_of_experience = $_POST['years_of_experience'] ?? '';

    if (empty($first_name) || empty($last_name) || empty($birthdate)) {
        throw new Exception('First name, last name, and birthdate are required');
    }

    if (strlen($middle_initial) > 1) {
        throw new Exception('Middle initial must be 1 character or less');
    }

    $sql = "UPDATE tutor SET 
            first_name = ?, 
            middle_initial = ?, 
            last_name = ?, 
            birthdate = ?, 
            educational_attainment = ?, 
            years_of_experience = ?,
            updated_at = NOW()
            WHERE tutor_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("ssssssi", 
        $first_name, 
        $middle_initial, 
        $last_name, 
        $birthdate, 
        $educational_attainment, 
        $years_of_experience,
        $tutor_id
    );

    if (!$stmt->execute()) {
        throw new Exception('Database update failed: ' . $stmt->error);
    }

    $response['success'] = true;
    $response['message'] = 'Profile updated successfully';
    
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Profile update error: " . $e->getMessage());
}

ob_end_clean();
echo json_encode($response);
exit();
?>