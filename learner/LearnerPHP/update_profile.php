<?php
session_start();
require_once '../../db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['learner_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$learner_id = $_SESSION['learner_id'];

if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['birthdate'])) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
    exit();
}

$first_name = trim($_POST['first_name']);
$middle_initial = trim($_POST['middle_initial']);
$last_name = trim($_POST['last_name']);
$birthdate = trim($_POST['birthdate']);
$school_affiliation = trim($_POST['school_affiliation']);
$grade_level = trim($_POST['grade_level']);
$strand = trim($_POST['strand']);

if (strlen($middle_initial) > 1) {
    $middle_initial = substr($middle_initial, 0, 1);
}

try {
    $sql = "UPDATE learner SET 
            first_name = ?, 
            middle_initial = ?, 
            last_name = ?, 
            birthdate = ?, 
            school_affiliation = ?, 
            grade_level = ?, 
            strand = ?
            WHERE learner_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssi", 
        $first_name, 
        $middle_initial, 
        $last_name, 
        $birthdate, 
        $school_affiliation, 
        $grade_level, 
        $strand, 
        $learner_id
    );
    
    if ($stmt->execute()) {
        $_SESSION['full_name'] = $first_name . ($middle_initial ? " {$middle_initial}. " : " ") . $last_name;
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $stmt->error]);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>