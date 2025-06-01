<?php
require_once '../../db_connection.php';

$admins = [];
$adminQuery = "SELECT * FROM admin ORDER BY admin_id ASC";
$adminResult = $conn->query($adminQuery);

if ($adminResult && $adminResult->num_rows > 0) {
    while ($row = $adminResult->fetch_assoc()) {
        $admins[] = $row;
    }
}

$tutors = [];
$tutorQuery = "SELECT * FROM tutor WHERE is_deleted = 0 ORDER BY tutor_id ASC";
$tutorResult = $conn->query($tutorQuery);

if ($tutorResult && $tutorResult->num_rows > 0) {
    while ($row = $tutorResult->fetch_assoc()) {
        $tutors[] = $row;
    }
}

$learners = [];
$learnerQuery = "SELECT * FROM learner WHERE is_deleted = 0 ORDER BY learner_id ASC";
$learnerResult = $conn->query($learnerQuery);

if ($learnerResult && $learnerResult->num_rows > 0) {
    while ($row = $learnerResult->fetch_assoc()) {
        $learners[] = $row;
    }
}
?>