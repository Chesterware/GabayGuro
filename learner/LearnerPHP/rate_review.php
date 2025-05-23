<?php
require_once '../../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = $_POST['booking_id'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $comment = $_POST['comment'] ?? null;

    if ($bookingId && $rating && $comment) {
        $stmt = $conn->prepare("SELECT tutor_id FROM bookings WHERE booking_id = ?");
        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        $stmt->bind_result($tutorId);
        $stmt->fetch();
        $stmt->close();

        if (!$tutorId) {
            echo json_encode(['success' => false, 'error' => 'Tutor not found.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT num_bookings, average_rating FROM tutor WHERE tutor_id = ?");
        $stmt->bind_param("i", $tutorId);
        $stmt->execute();
        $stmt->bind_result($numBookings, $averageRating);
        $stmt->fetch();
        $stmt->close();

        $numBookings = $numBookings ?: 0;
        $averageRating = $averageRating ?: 0;

        $newNumBookings = $numBookings + 1;
        $totalRating = ($numBookings * $averageRating) + $rating;
        $newAverageRating = $totalRating / $newNumBookings;

        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("UPDATE bookings SET reviewed = 1, rating = ?, review_text = ?, status = 'completed' WHERE booking_id = ?");
            $stmt->bind_param("dsi", $rating, $comment, $bookingId);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("UPDATE tutor SET num_bookings = ?, average_rating = ? WHERE tutor_id = ?");
            $stmt->bind_param("idi", $newNumBookings, $newAverageRating, $tutorId);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'error' => 'Transaction failed: ' . $e->getMessage()]);
        }

    } else {
        echo json_encode(['success' => false, 'error' => 'Missing data.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>