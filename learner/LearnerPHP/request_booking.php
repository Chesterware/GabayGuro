<?php
session_start();
require_once '../../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['learner_id'])) {
        echo "Error: Learner not logged in.";
        exit;
    }

    $learner_id = $_SESSION['learner_id'];
    $tutor_id = $_POST['tutor_id'] ?? null;
    $address = trim($_POST['address'] ?? '');
    $date = $_POST['date'] ?? null;
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $offer = $_POST['offer'] ?? '';
    $subject = $_POST['subject'] ?? null;

    if (!$tutor_id || !$date || !$start_time || !$end_time || !$subject) {
        echo "Error: All fields are required.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO bookings 
        (learner_id, tutor_id, address, date, start_time, end_time, offer, subject)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param("iissssss", $learner_id, $tutor_id, $address, $date, $start_time, $end_time, $offer, $subject);

    if ($stmt->execute()) {
        echo "Booking request sent successfully.";
        header('Location: ../LearnerHTML/find_tutors.php');  
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
