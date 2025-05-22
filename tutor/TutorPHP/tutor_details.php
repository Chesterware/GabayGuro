<?php
session_start();

require_once 'db_connection.php';

$tutorData = [];
$reviews = [];
$notifications = [];

if (isset($_SESSION['tutor_id'])) {
    $tutor_id = $_SESSION['tutor_id'];
    
    // Get tutor basic info
    $sql = "SELECT tutor_id, email, password, first_name, middle_initial, last_name, birthdate, 
            profile_picture, educational_attainment, years_of_experience, diploma, other_certificates, 
            rate_per_hour, rate_per_session, num_bookings, average_rating, status 
            FROM tutor WHERE tutor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tutor_id);
    $stmt->execute();
    $stmt->bind_result($tutor_id, $email, $password, $first, $mi, $last, $birthdate, $profile_picture, 
                      $educational_attainment, $years_of_experience, $diploma, $other_certificates, 
                      $rate_per_hour, $rate_per_session, $num_bookings, $average_rating, $status);

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

    // Get notifications
    $notifications = getTutorNotifications($tutor_id);
    
    // Get reviews
    $reviews = getTutorReviews($tutor_id);
}

function getTutorNotifications($tutor_id) {
    global $conn;
    
    $notifications = [];
    
    $sql = "SELECT b.booking_id, b.status, l.first_name, l.last_name, 
            CONCAT(b.status, ' - ', 
                CASE 
                    WHEN b.status = 'ACCEPTED' THEN CONCAT('You have accepted a booking from ', l.first_name, ' ', l.last_name)
                    WHEN b.status = 'DECLINED' THEN CONCAT('You have declined a booking from ', l.first_name, ' ', l.last_name)
                    WHEN b.status = 'CANCELLED' THEN CONCAT(l.first_name, ' ', l.last_name, ' has cancelled their booking with you')
                    WHEN b.status = 'COMPLETED' THEN CONCAT('Your session with ', l.first_name, ' ', l.last_name, ' has been completed')
                    ELSE CONCAT('Booking status changed to ', b.status, ' for ', l.first_name, ' ', l.last_name)
                END) as message,
            TIMESTAMPDIFF(HOUR, b.updated_at, NOW()) as hours_ago
            FROM bookings b
            JOIN learner l ON b.learner_id = l.learner_id
            WHERE b.tutor_id = ? AND b.status IN ('ACCEPTED', 'DECLINED', 'CANCELLED', 'COMPLETED')
            ORDER BY b.updated_at DESC
            LIMIT 10";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tutor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $time_ago = $row['hours_ago'] > 24 ? 
            floor($row['hours_ago'] / 24) . 'd ago' : 
            $row['hours_ago'] . 'h ago';
            
        $notifications[] = [
            'status' => $row['status'],
            'message' => $row['message'],
            'time_ago' => $time_ago
        ];
    }
    
    return $notifications;
}

function getTutorReviews($tutor_id) {
    global $conn;
    
    $reviews = [];
    
    $sql = "SELECT b.booking_id, l.first_name, l.last_name, l.profile_picture, 
            b.rating, b.review_text, DATE_FORMAT(b.updated_at, '%M %d, %Y') as review_date
            FROM bookings b
            JOIN learner l ON b.learner_id = l.learner_id
            WHERE b.tutor_id = ? AND b.reviewed = 1 AND b.rating IS NOT NULL
            ORDER BY b.updated_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tutor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $reviews[] = [
            'profile_picture' => $row['profile_picture'] ? $row['profile_picture'] : 'learner-icon.png',
            'rating' => $row['rating'],
            'review_text' => $row['review_text'],
        ];
    }
    return $reviews;
}
?>