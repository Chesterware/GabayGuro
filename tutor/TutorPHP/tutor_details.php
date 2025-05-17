<?php
session_start();

require_once 'db_connection.php';

$tutorData = [];

if (isset($_SESSION['tutor_id'])) {
    $tutor_id = $_SESSION['tutor_id'];
    $sql = "SELECT tutor_id, email, password, first_name, middle_initial, last_name, birthdate, profile_picture, educational_attainment, years_of_experience, diploma, other_certificates, rate_per_hour, rate_per_session, num_bookings, average_rating, status FROM tutor WHERE tutor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tutor_id);
    $stmt->execute();
    $stmt->bind_result($tutor_id, $email, $password, $first, $mi, $last, $birthdate, $profile_picture, $educational_attainment, $years_of_experience, $diploma, $other_certificates, $rate_per_hour, $rate_per_session, $num_bookings, $average_rating, $status);

    if ($stmt->fetch()) {
        $tutorData = [
            'tutor_id' => $tutor_id,
            'full_name' => $first . ' ' . ($mi ? $mi . '. ' : '') . $last,
            'email' => $email,
            'password' => $password,
            'birthdate' => $birthdate,
            'profile_picture' => $profile_picture,
            'educational_attainment' => $educational_attainment,
            'years_of_experience' => $years_of_experience,
            'diploma' => $diploma,
            'other_certificates' => $other_certificates,
            'rate_per_hour' => $rate_per_hour,
            'rate_per_session' => $rate_per_session,
            'num_bookings' => $num_bookings,
            'average_rating' => $average_rating,
            'status' => $status
        ];
    }

    $stmt->close();
}

?>