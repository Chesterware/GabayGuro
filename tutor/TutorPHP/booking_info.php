<?php
require_once 'db_connection.php';

if (!isset($_SESSION['tutor_id'])) {
    header("Location: ../../index.php");
    exit();
}

$tutor_id = $_SESSION['tutor_id'];

$sql = "SELECT b.*, 
               CONCAT(l.first_name, ' ', l.middle_initial, '. ', l.last_name) AS learner_name
        FROM bookings b
        JOIN learner l ON b.learner_id = l.learner_id
        WHERE b.tutor_id = ? 
        ORDER BY b.date DESC, b.start_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tutor_id);
$stmt->execute();

$result = $stmt->get_result();

if ($stmt->error) {
    error_log("SQL Error: " . $stmt->error, 3, "errors.log");
    echo "An error occurred while fetching bookings. Please try again later.";
    exit;
}

$stmt->close();
?>
