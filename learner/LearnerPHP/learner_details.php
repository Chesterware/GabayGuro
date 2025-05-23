<?php
require_once '../../db_connection.php';

$learnerData = [];

if (isset($_SESSION['learner_id'])) {
    $learner_id = $_SESSION['learner_id'];
    $sql = "SELECT learner_id, first_name, middle_initial, last_name, email, password, birthdate, school_affiliation, grade_level, strand FROM learner WHERE learner_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $learner_id);
    $stmt->execute();
    $stmt->bind_result($learner_id, $first, $mi, $last, $email, $password, $birthdate, $school_affiliation, $grade_level, $strand);

    if ($stmt->fetch()) {
        $learnerData = [
            'learner_id' => $learner_id,
            'full_name' => $first . ' ' . $mi . '. ' . $last,
            'email' => $email,
            'password' => $password,
            'birthdate' => $birthdate,
            'school_affiliation' => $school_affiliation,
            'grade_level' => $grade_level,
            'strand' => $strand
        ];
    }

    $stmt->close();
}
?>