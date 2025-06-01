<?php
require_once '../../db_connection.php';

$deletedTutors = [];
$tutorQuery = "SELECT * FROM tutor WHERE is_deleted = 1 ORDER BY updated_at DESC";
$tutorResult = $conn->query($tutorQuery);

if ($tutorResult && $tutorResult->num_rows > 0) {
    while ($row = $tutorResult->fetch_assoc()) {
        $deletedTutors[] = $row;
    }
}

$deletedLearners = [];
$learnerQuery = "SELECT * FROM learner WHERE is_deleted = 1 ORDER BY updated_at DESC";
$learnerResult = $conn->query($learnerQuery);

if ($learnerResult && $learnerResult->num_rows > 0) {
    while ($row = $learnerResult->fetch_assoc()) {
        $deletedLearners[] = $row;
    }
}

$totalDeletedUsers = count($deletedTutors) + count($deletedLearners);
?>