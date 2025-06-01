<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../LearnerPHP/learner_details.php';

if (!isset($_SESSION['learner_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

require_once '../../db_connection.php';

$first_name = $_POST['first-name'] ?? '';
$middle_name = $_POST['middle-name'] ?? '';
$last_name = $_POST['last-name'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$school_affiliation = $_POST['school-affiliation'] ?? '';
$grade_level = $_POST['grade-level'] ?? '';
$strand = $_POST['strand'] ?? '';
$learner_id = $_SESSION['learner_id'];

if (empty($first_name) || empty($last_name) || empty($birthdate)) {
    echo json_encode(['success' => false, 'message' => 'First name, last name, and birthdate are required']);
    exit();
}

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
$stmt->bind_param("sssssssi", 
    $first_name, 
    $middle_name, 
    $last_name, 
    $birthdate, 
    $school_affiliation, 
    $grade_level, 
    $strand, 
    $learner_id
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>