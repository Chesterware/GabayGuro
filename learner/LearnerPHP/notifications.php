<?php
require_once '../../db_connection.php';

if (!isset($_SESSION['learner_id'])) {
    header("Location: ../../index.php");
    exit(); 
}

$learner_id = $_SESSION['learner_id'];

$raw_statuses = ['ongoing', 'rejected', 'cancelled'];
$status_map = [
    'ongoing' => 'ACCEPTED',
    'rejected' => 'DECLINED',
    'cancelled' => 'CANCELLED'
];

$status_filter = isset($_GET['status']) ? strtolower($_GET['status']) : null;
$use_filter = in_array($status_filter, $raw_statuses);

$sql = "SELECT b.*, 
               CONCAT(t.first_name, ' ', t.middle_initial, '. ', t.last_name) AS tutor_name,
               b.status
        FROM bookings b
        JOIN tutor t ON b.tutor_id = t.tutor_id
        WHERE b.learner_id = ? AND b.is_deleted = 0";

if ($use_filter) {
    $sql .= " AND LOWER(b.status) = ?";
} else {
    $placeholders = implode("','", $raw_statuses);
    $sql .= " AND LOWER(b.status) IN ('$placeholders')";
}

$sql .= " ORDER BY b.updated_at DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Error preparing statement: " . $conn->error, 3, "errors.log");
    die("An error occurred while preparing the statement.");
}

if ($use_filter) {
    $stmt->bind_param("is", $learner_id, $status_filter);
} else {
    $stmt->bind_param("i", $learner_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($stmt->error) {
    error_log("SQL Error: " . $stmt->error, 3, "errors.log");
    echo "An error occurred while fetching bookings. Please try again later.";
    exit;
}

$notifications = [];
date_default_timezone_set('Asia/Manila');
$now = new DateTime();

while ($row = $result->fetch_assoc()) {
    $updated_at = new DateTime($row['updated_at']);
    $interval = $now->diff($updated_at);

    if ($interval->d > 0) {
        $time_ago = $interval->d . 'd ago';
    } elseif ($interval->h > 0) {
        $time_ago = $interval->h . 'h ago';
    } elseif ($interval->i > 0) {
        $time_ago = $interval->i . 'm ago';
    } else {
        $time_ago = 'Just now';
    }

    $raw_status = strtolower($row['status']);
    $row['display_status'] = isset($status_map[$raw_status]) ? $status_map[$raw_status] : strtoupper($row['status']);
    $row['time_ago'] = $time_ago;

    $notifications[] = $row;
}
?>