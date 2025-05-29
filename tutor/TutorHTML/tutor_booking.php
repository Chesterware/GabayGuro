<?php
require_once '../TutorPHP/tutor_details.php';
require_once '../TutorPHP/booking_info.php';
require_once '../TutorPHP/auth_tutor.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../TutorCSS/index.css">
    <link rel="stylesheet" href="../TutorCSS/tutor_booking.css">
</head>
<style>
    .booking-status-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
    gap: 12px;
    margin: 100px 10px 0 240px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

body.sidebar-collapsed .booking-status-buttons {
    margin: 100px 10px 0 10px;
}

.status-btn {
    padding: 12px 10px;
    background-color: transparent;
    color: #003153;
    border: 2px solid #003153;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    text-decoration: none;
    text-align: center;
    white-space: nowrap;
}

.status-btn:hover, .status-btn.status-selected {
    background-color: #003153;
    color: white;
    font-weight: bold;
}

.learner-booking-entry {
    display: flex;
    align-items: center;
    background-color: #FFFFFF;
    padding: 15px;
    margin: 20px 10px 0 240px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: relative;
    transition: all 0.3s ease;
}

body.sidebar-collapsed .learner-booking-entry {
    margin: 20px 10px 0 10px;
}

.learner-col {
    padding: 8px;
    box-sizing: border-box;
}

.learner-col-left {
    width: 15%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.learner-image {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #003153;
}

.learner-col-labels {
    width: 15%;
    color: #8B8B8B;
    font-size: 0.85rem;
}

.learner-col-values {
    width: 45%;
    color: #003153;
    font-weight: bold;
    font-size: 0.9rem;
}

.learner-info-line {
    margin: 6px 0;
    line-height: 1.3;
}

.learner-action-buttons {
    width: 25%;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}

.accept-btn, .decline-btn {
    padding: 8px 12px;
    font-size: 0.85rem;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    width: 100px;
}

.accept-btn {
    background-color: #003153;
}

.accept-btn:hover {
    background-color: #002440;
}

.decline-btn {
    border: 2px solid #003153;
    background-color: transparent;
    color: #003153;
}

.decline-btn:hover {
    background-color: #002440;
    color: white;
}

.no-bookings-box {
    background-color: #FFFFFF;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
    text-align: left;
    font-size: 16px;
    color: #003153;
    font-weight: bold;
    width: 100%;
    margin: 20px 10px 0 240px;
    transition: all ease 0.3s;
}

body.sidebar-collapsed .no-bookings-box {
    margin: 20px 10px 0 10px;
}

@media (max-width: 768px) {
    .no-bookings-box {
        margin: 20px 10px;
        font-size: 14px;
        padding: 15px;
    }
}

@media (max-width: 1024px) {
    .booking-status-buttons {
        grid-template-columns: repeat(3, 1fr);
        margin: 90px 10px 0 10px;
        position: sticky;
        top: 70px;
        background: #E6ECF1;
        padding: 10px 0;
        z-index: 700;
    }
    
    .learner-booking-entry {
        margin: 15px 10px;
        flex-direction: column;
        text-align: center;
    }
    
    .learner-col-left,
    .learner-col-labels,
    .learner-col-values,
    .learner-action-buttons {
        width: 100%;
    }
    
    .learner-col-labels {
        margin-top: 10px;
        text-align: center;
    }
    
    .learner-action-buttons {
        justify-content: center;
        margin-top: 15px;
    }
    
    .learner-image {
        width: 80px;
        height: 80px;
    }
    
    .accept-btn, .decline-btn {
        width: 120px;
        padding: 10px;
    }
}

@media (max-width: 768px) {
    .booking-status-buttons {
        grid-template-columns: repeat(2, 1fr);
        top: 60px;
    }
    
    .status-btn {
        padding: 10px 8px;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .booking-status-buttons {
        grid-template-columns: 1fr;
        gap: 8px;
        top: 60px;
    }
    
    .learner-booking-entry {
        padding: 12px;
    }
    
    .learner-col-labels,
    .learner-col-values {
        font-size: 0.8rem;
    }
    
    .accept-btn, .decline-btn {
        width: 100%;
        font-size: 0.8rem;
    }
    
    .learner-image {
        width: 70px;
        height: 70px;
    }
}
</style>
<body class="sidebar-collapsed">
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
                <div class="learner-booking-entry"
                    style="display: none;"
                    data-booking-id="<?php echo $row['booking_id']; ?>"
                    data-status="<?php echo strtolower($row['status']); ?>">

                    <div class="learner-col learner-col-left">
                        <img src="../../tutor-icon.png" alt="Learner Photo" class="learner-image">
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
        <script src="../../time-date-sidebar.js"></script>
</body>
</html>