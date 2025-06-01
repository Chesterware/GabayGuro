<?php
require_once '../../db_connection.php';

$statuses = ['for verification', 'verified', 'unverified'];

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../index.php");
    exit();
}

$tutors_by_status = [
    'for verification' => [],
    'verified' => [],
    'unverified' => [],
];

$sql = "SELECT 
            t.tutor_id,
            t.first_name,
            t.middle_initial,
            t.last_name,
            t.educational_attainment,
            t.years_of_experience,
            t.rate_per_hour,
            t.rate_per_session,
            t.created_at,
            t.updated_at,
            t.status,
            s.specialization_name
        FROM tutor t
        LEFT JOIN tutor_specializations ts ON t.tutor_id = ts.tutor_id
        LEFT JOIN specializations s ON ts.specialization_id = s.specialization_id
        WHERE t.is_deleted = 0
        ORDER BY t.status, t.updated_at DESC";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $status = strtolower(trim($row['status']));
        $tutor_id = $row['tutor_id'];

        if (!isset($tutors_by_status[$status])) {
            $tutors_by_status[$status] = [];
        }

        if (!isset($tutors_by_status[$status][$tutor_id])) {
            $tutors_by_status[$status][$tutor_id] = [
                'tutor_id' => $tutor_id,
                'first_name' => $row['first_name'],
                'middle_initial' => $row['middle_initial'],
                'last_name' => $row['last_name'],
                'educational_attainment' => $row['educational_attainment'],
                'years_of_experience' => $row['years_of_experience'],
                'rate_per_hour' => $row['rate_per_hour'],
                'rate_per_session' => $row['rate_per_session'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'specializations' => [],
            ];
        }

        if ($row['specialization_name']) {
            $tutors_by_status[$status][$tutor_id]['specializations'][] = $row['specialization_name'];
        }
    }
} else {
    die("Database query failed: " . $conn->error);
}

foreach ($tutors_by_status as $status => $tutors_assoc) {
    $tutors_indexed = array_values($tutors_assoc);

    usort($tutors_indexed, function($a, $b) {
        return strtotime($b['updated_at']) <=> strtotime($a['updated_at']);
    });

    $tutors_sorted = [];
    foreach ($tutors_indexed as $tutor) {
        $tutors_sorted[$tutor['tutor_id']] = $tutor;
    }

    $tutors_by_status[$status] = $tutors_sorted;
}
?>