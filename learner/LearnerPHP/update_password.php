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

$password = $_POST['password'] ?? '';

if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$learner_id = $_SESSION['learner_id'];

$sql = "UPDATE learner SET password = ? WHERE learner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $hashed_password, $learner_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();
?>