<?php
require_once 'db_connection.php';

error_log('SESSION tutor_id: ' . ($_SESSION['tutor_id'] ?? 'not set'));

if (!isset($_SESSION['tutor_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo 'unauthorized';
    exit;
}

$bookingId = $_POST['booking_id'] ?? null;
$status = $_POST['status'] ?? null;
$allowedStatuses = ['ongoing', 'rejected'];

if (!$bookingId || !in_array($status, $allowedStatuses)) {
    http_response_code(400);
    echo 'invalid';
    exit;
}

$sql = "UPDATE bookings SET status = ? WHERE booking_id = ? AND tutor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $status, $bookingId, $_SESSION['tutor_id']);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'error';
}
?>
