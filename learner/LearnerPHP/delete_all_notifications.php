<?php
require_once 'db_connection.php';

session_start(); // Make sure session is started
if (!isset($_SESSION['learner_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$learner_id = $_SESSION['learner_id'];
$valid_statuses = ['ongoing', 'rejected', 'cancelled'];

$placeholders = implode(',', array_fill(0, count($valid_statuses), '?'));

$sql = "UPDATE bookings 
        SET is_deleted = 1 
        WHERE learner_id = ? AND is_deleted = 0 AND status IN ($placeholders)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

$params = array_merge([$learner_id], $valid_statuses);
$types = str_repeat('s', count($valid_statuses));
$types = 'i' . $types;
$stmt->bind_param($types, ...$params);

$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No notifications to delete or already deleted']);
}
?>