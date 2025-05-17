<?php
require_once '../LearnerPHP/learner_details.php';
require_once '../LearnerPHP/booking_info.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../LearnerCSS/booking_history.css">
</head>

<body>
    <div class="header-title">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="page-label">BOOKING HISTORY</div>
        <div class="datetime" id="datetime"></div>
    </div>

    <div class="sidebar">
        <h2 class="learner-name"><?php echo htmlspecialchars($learnerData['full_name']); ?></h2>
        <h3 class="sidebar-label">LEARNER</h3>

        <div class="separator"></div>

        <form action="find_tutors.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-search"></i>Find Tutors</button>
        </form>
        <form action="notifications.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-bell"></i>Notifications</button>
        </form>
        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-history"></i>Booking History</button>
        </form>
        <form action="learner_profile.php" method="GET">
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
        <a href="#" class="status-btn booking-pending <?php echo ($status_filter === 'pending') ? 'status-selected' : ''; ?>" onclick="selectStatus(event, 'pending')">PENDING</a>
        <a href="#" class="status-btn booking-ongoing <?php echo ($status_filter === 'ongoing') ? 'status-selected' : ''; ?>" onclick="selectStatus(event, 'ongoing')">ONGOING</a>
        <a href="#" class="status-btn booking-for-review <?php echo ($status_filter === 'for review') ? 'status-selected' : ''; ?>" onclick="selectStatus(event, 'for review')">FOR REVIEW</a>
        <a href="#" class="status-btn booking-completed <?php echo ($status_filter === 'completed') ? 'status-selected' : ''; ?>" onclick="selectStatus(event, 'completed')">COMPLETED</a>
        <a href="#" class="status-btn booking-rejected <?php echo ($status_filter === 'rejected') ? 'status-selected' : ''; ?>" onclick="selectStatus(event, 'rejected')">DECLINED</a>
        <a href="#" class="status-btn booking-cancelled <?php echo ($status_filter === 'cancelled') ? 'status-selected' : ''; ?>" onclick="selectStatus(event, 'cancelled')">CANCELLED</a>
    </div>

    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <h3>Write a Review</h3>
            <div class="stars">
                <i class="fas fa-star" onclick="selectStar(1)"></i>
                <i class="fas fa-star" onclick="selectStar(2)"></i>
                <i class="fas fa-star" onclick="selectStar(3)"></i>
                <i class="fas fa-star" onclick="selectStar(4)"></i>
                <i class="fas fa-star" onclick="selectStar(5)"></i>
            </div>
            <textarea id="comment" placeholder="Write your experience here..."></textarea>
            <div class="modal-buttons">
                <button class="modal-button cancel-button" id="cancelButton">CANCEL</button>
                <button class="modal-button confirm-button">SUBMIT</button>
            </div>
        </div>
    </div>

    <?php if ($result->num_rows > 0) : ?>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="booking-entry full-view" data-booking-id="<?php echo $row['booking_id']; ?>" data-status="<?php echo strtolower($row['status']); ?>">
                <div class="booking-col booking-col-left">
                    <img src="tutor.jpg" alt="Tutor Image" class="tutor-image">
                </div>

                <div class="booking-col booking-col-labels">
                    <div class="booking-info-line">Tutor:</div>
                    <div class="booking-info-line">Subject:</div>
                    <div class="booking-info-line">Time:</div>
                    <div class="booking-info-line">Date:</div>
                    <div class="booking-info-line">Location:</div>
                    <div class="booking-info-line">Offer:</div>
                </div>

                <div class="booking-col booking-col-values">
                    <div class="booking-info-line"><?php echo htmlspecialchars($row['tutor_name']); ?></div>
                    <div class="booking-info-line"><?php echo htmlspecialchars($row['subject']); ?></div>
                    <div class="booking-info-line"><?php echo date("h:i A", strtotime($row['start_time'])) . " - " . date("h:i A", strtotime($row['end_time'])); ?></div>
                    <div class="booking-info-line"><?php echo date("F j, Y", strtotime($row['date'])); ?></div>
                    <div class="booking-info-line"><?php echo htmlspecialchars($row['address']); ?></div>
                    <div class="booking-info-line">P <?php echo htmlspecialchars($row['offer']); ?></div>
                </div>

                <div class="action-btn-container">
                    <?php if ($row['status'] === 'pending'): ?>
                        <button class="cancel-btn" id="cancelBtn<?php echo $row['booking_id']; ?>">CANCEL</button>
                    <?php elseif ($row['status'] === 'ongoing'): ?>
                        <button class="finish-btn" id="finishBtn<?php echo $row['booking_id']; ?>">FINISH</button>
                    <?php elseif ($row['status'] === 'for review'): ?>
                        <button class="write-review-btn" id="writeReviewBtn<?php echo $row['booking_id']; ?>">WRITE REVIEW</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="booking-entry full-view">
            <div class="booking-info-line">
                <div class="no-bookings">No bookings found.</div>
            </div>
        </div>
    <?php endif; ?>
    
    <script src="../LearnerJS/booking_history.js"></script>
</body>
</html>