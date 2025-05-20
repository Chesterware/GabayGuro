<?php
require_once '../TutorPHP/tutor_details.php';

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
    <title>Ratings & Reveiw</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../TutorCSS/ratings_review.css">
    <script src="../TutorJS/ratings_review.js"></script>
</head>
<body>
    <div class="header-title">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="page-label">RATINGS / REVIEW</div>
        <div class="datetime" id="datetime"></div>
    </div>

    <div class="sidebar">
        <h2 class="logged-in-tutor"><?php echo htmlspecialchars($tutorData['full_name']); ?></h2>
        <h3 class="sidebar-label">TUTOR</h3>

        <div class="separator"></div>

        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-star"></i>Ratings / Review</button>
        </form>
        <form action="tutor_booking.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-history"></i>Booking History</button>
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
    
    <div class="tutor-rating sidebar-open">
        <div class="icon-column">
            <img class="tutor-icon" src="tutor-icon.png" alt="Tutor Icon">
        </div>
        <div class="info-column">
            <p class="tutor-name"><?php echo htmlspecialchars($tutorData['full_name']); ?></p>
            <p class="rating"><span class="label">Rating:</span> <?php echo number_format($tutorData['average_rating'], 1); ?> / 5.0</p>
            
            <p class="total-bookings"><span class="label">Total Bookings:</span> <?php echo number_format($tutorData['num_bookings']); ?></p>
        </div>
    </div>

    <div class="learner-review sidebar-open">
        <div class="learner-icon-column">
            <img class="learner-icon" src="learner-icon.png" alt="Learner Icon">
        </div>

        <div class="learner-text-column">
            <p class="learner-review-text">
                John was an amazing tutor! He explained the concepts clearly and was really patient with my questions. 
            </p>x`cx
        </div>
    </div>

    
</body>
</html>
