<?php
require_once 'db_connection.php';

$education = $_GET['education'] ?? '';
$experience = $_GET['experience'] ?? '';
$rate_type = $_GET['rate_type'] ?? '';
$subject_specializations = $_GET['specialization'] ?? [];
$sort_by = $_GET['sort_by'] ?? '';

$min_rate = isset($_GET['min_rate']) && is_numeric($_GET['min_rate']) ? (float)$_GET['min_rate'] : null;
$max_rate = isset($_GET['max_rate']) && is_numeric($_GET['max_rate']) ? (float)$_GET['max_rate'] : null;

$types = '';
$params = [];

$tutors = [];
$no_tutors_found = false;

$learner_preferences = [
    'experience' => 3,
    'rate' => 100,
    'rating' => 4,
    'specializations' => [1, 2]
];

function calculate_specialization_match_score($tutor_specializations, $learner_specializations) {
    $match_score = 0;
    foreach ($tutor_specializations as $specialization) {
        if (in_array($specialization, $learner_specializations)) {
            $match_score++;
        }
    }
    return $match_score;
}

function cosine_similarity($vector1, $vector2) {
    $dot_product = 0;
    $magnitude1 = 0;
    $magnitude2 = 0;

    for ($i = 0; $i < count($vector1); $i++) {
        $dot_product += $vector1[$i] * $vector2[$i];
        $magnitude1 += pow($vector1[$i], 2);
        $magnitude2 += pow($vector2[$i], 2);
    }

    $magnitude1 = sqrt($magnitude1);
    $magnitude2 = sqrt($magnitude2);

    return ($magnitude1 == 0 || $magnitude2 == 0) ? 0 : ($dot_product / ($magnitude1 * $magnitude2));
}

function generate_tutor_vector($tutor) {
    $experience_map = [
        'Less than 1 year' => 0,
        '1-3 years' => 1,
        '4-6 years' => 2,
        '7+ years' => 3
    ];
    $vector = [
        $experience_map[$tutor['years_of_experience']] ?? 0,
        $tutor['rate_per_hour'] ?? 0,
        $tutor['average_rating'] ?? 0,
    ];
    for ($i = 1; $i <= 5; $i++) {
        $vector[] = in_array($i, $tutor['specializations']) ? 1 : 0;
    }
    return $vector;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['apply_filters'])) {
    $sql = "
        SELECT 
            t.tutor_id, 
            t.first_name, 
            t.last_name, 
            t.educational_attainment, 
            t.years_of_experience, 
            t.rate_per_hour,
            t.rate_per_session,  
            t.status,
            t.num_bookings, 
            t.average_rating,
            (
                SELECT GROUP_CONCAT(DISTINCT ts1.specialization_id ORDER BY ts1.specialization_id SEPARATOR ',') 
                FROM tutor_specializations ts1 
                WHERE ts1.tutor_id = t.tutor_id
            ) AS specialization_ids,
            (
                SELECT GROUP_CONCAT(DISTINCT s1.specialization_name ORDER BY s1.specialization_name SEPARATOR ', ')
                FROM tutor_specializations ts1
                JOIN specializations s1 ON ts1.specialization_id = s1.specialization_id
                WHERE ts1.tutor_id = t.tutor_id
            ) AS specialization_names
        FROM tutor t
        WHERE 1=1
    ";

    if (!empty($education)) {
        $sql .= " AND t.educational_attainment = ?";
        $types .= 's';
        $params[] = $education;
    }

    $experienceOptions = ['Less than 1 year', '1-3 years', '4-6 years', '7+ years'];
    if (in_array($experience, $experienceOptions)) {
        $sql .= " AND t.years_of_experience = ?";
        $types .= 's';
        $params[] = $experience;
    }

    if ($rate_type === 'session') {
        $sql .= " AND t.rate_per_session IS NOT NULL";
        if ($min_rate !== null) {
            $sql .= " AND t.rate_per_session >= ?";
            $types .= 'd';
            $params[] = $min_rate;
        }
        if ($max_rate !== null) {
            $sql .= " AND t.rate_per_session <= ?";
            $types .= 'd';
            $params[] = $max_rate;
        }
    } elseif ($rate_type === 'hour') {
        $sql .= " AND t.rate_per_hour IS NOT NULL";
        if ($min_rate !== null) {
            $sql .= " AND t.rate_per_hour >= ?";
            $types .= 'd';
            $params[] = $min_rate;
        }
        if ($max_rate !== null) {
            $sql .= " AND t.rate_per_hour <= ?";
            $types .= 'd';
            $params[] = $max_rate;
        }
    }

    if (!empty($subject_specializations)) {
        $placeholders = implode(',', array_fill(0, count($subject_specializations), '?'));
        $sql .= " AND t.tutor_id IN (
            SELECT ts2.tutor_id 
            FROM tutor_specializations ts2 
            WHERE ts2.specialization_id IN ($placeholders)
        )";
        $types .= str_repeat('i', count($subject_specializations));
        $params = array_merge($params, array_map('intval', $subject_specializations));
    }

    $sql .= " ORDER BY 
                FIELD(t.status, 'VERIFIED') DESC,
                FIELD(t.status, '') DESC,
                FIELD(t.status, 'UNVERIFIED') DESC,
                t.average_rating DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $tutors = $result->fetch_all(MYSQLI_ASSOC);

    if (count($tutors) === 0) {
        $no_tutors_found = true;
    }

    foreach ($tutors as &$tutor) {
        $tutor['specializations'] = array_map('intval', explode(',', $tutor['specialization_ids']));
        $tutor['specialization_names'] = explode(', ', $tutor['specialization_names']);
        $specialization_match_score = calculate_specialization_match_score($tutor['specializations'], $subject_specializations);
        $tutor['specialization_match_score'] = $specialization_match_score;

        $tutor_vector = generate_tutor_vector($tutor);
        $learner_vector = [
            $learner_preferences['experience'],
            $learner_preferences['rate'],
            $learner_preferences['rating'],
            ...array_map(function ($specialization) use ($learner_preferences) {
                return in_array($specialization, $learner_preferences['specializations']) ? 1 : 0;
            }, range(1, 5))
        ];
        $tutor['similarity_score'] = cosine_similarity($learner_vector, $tutor_vector);

        $review_stmt = $conn->prepare("
            SELECT rating, review_text 
            FROM bookings 
            WHERE tutor_id = ? AND reviewed = 1 AND review_text IS NOT NULL
            ORDER BY updated_at DESC
        ");
        $review_stmt->bind_param("i", $tutor['tutor_id']);
        $review_stmt->execute();
        $review_result = $review_stmt->get_result();
        $tutor['reviews'] = $review_result->fetch_all(MYSQLI_ASSOC);
        $review_stmt->close();
    }

    usort($tutors, function($a, $b) use ($sort_by) {
        if ($a['specialization_match_score'] !== $b['specialization_match_score']) {
            return $b['specialization_match_score'] <=> $a['specialization_match_score'];
        }

        $status_priority = [
            'Verified' => 3,
            'For Verification' => 2,
            'Unverified' => 1,
            'N/A' => 0
        ];

        $status_a = $status_priority[$a['status']] ?? 0;
        $status_b = $status_priority[$b['status']] ?? 0;
        $status_comparison = $status_b <=> $status_a;
        if ($status_comparison !== 0) {
            return $status_comparison;
        }

        switch ($sort_by) {
            case 'name_asc':
                return strcmp($a['first_name'], $b['first_name']);
            case 'name_desc':
                return strcmp($b['first_name'], $a['first_name']);
            case 'rating_desc':
                return $b['average_rating'] <=> $a['average_rating'];
            case 'rate_asc':
                return ($a['rate_per_hour'] ?? 0) <=> ($b['rate_per_hour'] ?? 0);
            case 'experience_desc':
                return strcmp($b['years_of_experience'], $a['years_of_experience']);
            default:
                return $b['similarity_score'] <=> $a['similarity_score'];
        }
    });
}
?>
