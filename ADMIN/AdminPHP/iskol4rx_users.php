<?php
require_once 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_query = "SELECT * FROM admin";
$admin_result = $conn->query($admin_query);
$admins = [];
if ($admin_result && $admin_result->num_rows > 0) {
    while ($row = $admin_result->fetch_assoc()) {
        $admins[] = $row;
    }
}

$tutor_query = "SELECT * FROM tutor";
$tutor_result = $conn->query($tutor_query);
$tutors = [];
if ($tutor_result && $tutor_result->num_rows > 0) {
    while ($row = $tutor_result->fetch_assoc()) {
        $tutors[] = $row;
    }
}

$learner_query = "SELECT * FROM learner";
$learner_result = $conn->query($learner_query);
$learners = [];
if ($learner_result && $learner_result->num_rows > 0) {
    while ($row = $learner_result->fetch_assoc()) {
        $learners[] = $row;
    }
}
?>