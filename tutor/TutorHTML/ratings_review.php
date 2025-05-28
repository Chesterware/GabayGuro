<?php
require_once '../TutorPHP/tutor_details.php';
require_once '../TutorPHP/auth_tutor.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ratings & Reveiw</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../TutorCSS/index.css">
    <link rel="stylesheet" href="../TutorCSS/ratings_review.css">
</head>
<body class="sidebar-collapsed">
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
    
    <div class="tutor-rating">
        <div class="icon-column">
            <img class="tutor-icon" src="<?= htmlspecialchars($profileImageSrc) ?>" alt="Tutor Profile Picture">
        </div>

        <div class="info-column">
            <p class="tutor-name"><?php echo htmlspecialchars($tutorData['full_name']); ?></p>
            <p class="rating"><span class="label">Rating:</span> <?php echo number_format($tutorData['average_rating'], 1); ?> / 5.0</p>
            
            <p class="total-bookings"><span class="label">Total Bookings:</span> <?php echo number_format($tutorData['num_bookings']); ?></p>
        </div>
    </div>

   <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="learner-review">
                <div class="learner-icon-column">
                    <img class="learner-icon" src="<?php echo htmlspecialchars($review['profile_picture']); ?>" alt="Learner Icon">
                </div>
                <div class="learner-text-column">
                    <div class="review-rating">
                        <?php displayStars($review['rating']); ?>
                        <span class="rating-value"><?php echo number_format($review['rating'], 1); ?></span>
                    </div>
                    <p class="learner-review-text">
                        <?php echo htmlspecialchars($review['review_text']); ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-reviews">
            <p>No reviews yet. Your reviews will appear here once learners rate your sessions.</p>
        </div>
    <?php endif; ?>

    <?php
    function displayStars($rating) {
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        
        for ($i = 0; $i < $fullStars; $i++) {
            echo '<i class="fas fa-star"></i>';
        }
        if ($halfStar) {
            echo '<i class="fas fa-star-half-alt"></i>';
        }
        for ($i = 0; $i < $emptyStars; $i++) {
            echo '<i class="far fa-star"></i>';
        }
    }
    ?>

    <script src="../../time-date-sidebar.js">
    </script>
</body>
</html>