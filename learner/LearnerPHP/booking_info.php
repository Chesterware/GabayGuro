<?php
require_once 'db_connection.php'; 

if (!isset($_SESSION['learner_id'])) {
    header("Location: ../../login.php");
    exit(); 
}

$learner_id = $_SESSION['learner_id'];
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'pending';

$sql = "SELECT b.*, 
               CONCAT(t.first_name, ' ', t.middle_initial, '. ', t.last_name) AS tutor_name
        FROM bookings b
        JOIN tutor t ON b.tutor_id = t.tutor_id
        WHERE b.learner_id = ? AND LOWER(b.status) = ? 
        ORDER BY b.date DESC, b.start_time DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Error preparing statement: " . $conn->error, 3, "errors.log");
    die("An error occurred while preparing the statement.");
}

$stmt->bind_param("is", $learner_id, $status_filter);
$stmt->execute();
$result = $stmt->get_result();

if ($stmt->error) {
    error_log("SQL Error: " . $stmt->error, 3, "errors.log");
    echo "An error occurred while fetching bookings. Please try again later.";
    exit;
}

?>