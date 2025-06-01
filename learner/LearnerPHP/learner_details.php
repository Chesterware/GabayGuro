<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../db_connection.php';

$learnerData = [];

if (isset($_SESSION['learner_id'])) {
    $learner_id = $_SESSION['learner_id'];
    $sql = "SELECT first_name, middle_initial, last_name, email, birthdate, 
                   school_affiliation, grade_level, strand 
            FROM learner 
            WHERE learner_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $learner_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $learnerData = [
            'full_name' => $row['first_name'] . ' ' . $row['middle_initial'] . '. ' . $row['last_name'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_initial'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'birthdate' => $row['birthdate'],
            'school_affiliation' => $row['school_affiliation'],
            'grade_level' => $row['grade_level'],
            'strand' => $row['strand']
        ];
    }
    $stmt->close();
}
?>