<?php
require_once 'db_connection.php';

session_start();
if (!isset($_SESSION['learner_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['booking_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing booking_id']);
    exit;
}

$learner_id = $_SESSION['learner_id'];
$booking_id = $data['booking_id'];

$sql = "UPDATE bookings 
        SET is_deleted = 1 
        WHERE booking_id = ? AND learner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $learner_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Notification not found or already deleted']);
}
?>