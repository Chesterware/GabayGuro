<?php
require_once '../TutorPHP/tutor_details.php';
require_once '../TutorPHP/booking_info.php';

if (!isset($_SESSION['tutor_id'])) {
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../TutorCSS/tutor_booking.css">
</head>
<style>

</style>
<body>
    <div class="header-title">  
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="page-label">BOOKING HISTORY</div>
        <div class="datetime" id="datetime"></div>
    </div>

    <div class="sidebar">
        <h2 class="logged-in-tutor"><?php echo htmlspecialchars($tutorData['full_name']); ?></h2>
        <h3 class="sidebar-label">TUTOR</h3>

        <div class="separator"></div>

        <form action="ratings_review.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-star"></i>Ratings / Review</button>
        </form>
        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-history"></i>Booking History</button>
        </form>
        <form action="tutor_profile.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-user-cog"></i>My Profile</button>
        </form>

        <div class="separator"></div>

        <form action="../../api/logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>

    <div class="booking-status-buttons">
        <a href="#" class="status-btn booking-pending status-selected" onclick="selectStatus(event, 'pending')">PENDING</a>
        <a href="#" class="status-btn booking-ongoing" onclick="selectStatus(event, 'ongoing')">ONGOING</a>
        <a href="#" class="status-btn booking-completed" onclick="selectStatus(event, 'completed')">COMPLETED</a>
        <a href="#" class="status-btn booking-rejected" onclick="selectStatus(event, 'rejected')">REJECTED</a>
        <a href="#" class="status-btn booking-cancelled" onclick="selectStatus(event, 'cancelled')">CANCELLED</a>
    </div>

    <div class="booking-entries">
        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="learner-booking-entry full-view"
                    style="display: none;"
                    data-booking-id="<?php echo $row['booking_id']; ?>"
                    data-status="<?php echo strtolower($row['status']); ?>">

                    <div class="learner-col learner-col-left">
                        <img src="learner.jpg" alt="Learner Photo" class="learner-image">
                    </div>

                    <div class="learner-col learner-col-labels">
                        <div class="learner-info-line">Learner:</div>
                        <div class="learner-info-line">Subject:</div>
                        <div class="learner-info-line">Time:</div>
                        <div class="learner-info-line">Date:</div>
                        <div class="learner-info-line">Location:</div>
                        <div class="learner-info-line">Offer:</div>
                    </div>

                    <div class="learner-col learner-col-values">
                        <div class="learner-info-line"><?php echo htmlspecialchars($row['learner_name']); ?></div>
                        <div class="learner-info-line"><?php echo htmlspecialchars($row['subject']); ?></div>
                        <div class="learner-info-line"><?php echo date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time'])); ?></div>
                        <div class="learner-info-line"><?php echo date("F j, Y", strtotime($row['date'])); ?></div>
                        <div class="learner-info-line"><?php echo htmlspecialchars($row['address']); ?></div>
                        <div class="learner-info-line">P <?php echo htmlspecialchars($row['offer']); ?></div>
                    </div>

                    <div class="learner-col learner-action-buttons">
                        <?php if ($row['status'] === 'pending') : ?>
                            <button class="accept-btn">ACCEPT</button>
                            <button class="decline-btn">DECLINE</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No bookings found.</p>
        <?php endif; ?>
        </div>

        <script src="../TutorJS/tutor_booking.js"></script>
</body>
</html>