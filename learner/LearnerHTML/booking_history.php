<?php
require_once '../LearnerPHP/learner_details.php';
require_once '../LearnerPHP/booking_info.php';
require_once '../LearnerPHP/auth_learner.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Find Tutors</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003153">
    <link rel="icon" href="/GabayGuroLogo.png">
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../LearnerCSS/index.css" />
    <link rel="stylesheet" href="../LearnerCSS/booking_history.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    .booking-status-buttons {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 8px;
    margin: 130px 10px 15px 10px;
    padding: 0 5px;
    transition: margin-left 0.3s ease;
}

body.sidebar-collapsed .booking-status-buttons {
    margin: 130px 10px 15px 10px;
}

@media (min-width: 768px) {
    .booking-status-buttons {
        grid-template-columns: repeat(6, 1fr);
        margin: 100px 20px 20px 240px;
        gap: 10px;
    }

    body.sidebar-collapsed .booking-status-buttons {
        margin: 100px 20px 20px 20px;
    }
}

.status-btn {
    padding: 8px 5px;
    font-size: 12px;
    background-color: transparent;
    color: #003153;
    border: 2px solid #003153;
    border-radius: 5px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-decoration: none;
    text-overflow: ellipsis;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-btn.status-selected,
.status-btn:hover {
    background-color: #003153;
    color: white;
    font-weight: bold;
}

.booking-row {
    display: flex;
    gap: 10px;
    margin: 0 10px 10px 240px;
    transition: all ease 0.3s;
}

body.sidebar-collapsed .booking-row {
    margin: 0 10px 10px 10px;
}

.booking-entry {
    flex: 0 1 50%;
    max-width: 100%;
    min-width: 300px;
    border: 2px solid #003153;
    padding: 15px;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.booking-info-container {
    display: flex;
    gap: 10px;
}

.booking-col {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.booking-col-left {
    flex: 0 0 100px;
}

.tutor-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #003153;
}

.booking-col-labels {
    flex: 0 0 80px;
    color: #000000;
}

.booking-col-values {
    flex: 1;
    color: #003153;
    font-weight: bold;
}

.booking-info-line {
    margin-bottom: 6px;
}

.action-btn-container {
    display: flex;
    justify-content: center;
    gap: 10px;
    width: 100%;
    margin-top: 10px;
}

.action-btn-container button {
    padding: 12px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    background-color: #003153;
    color: white;
    width: 100%;
}

.cancel-btn, .finish-btn, .write-review-btn {
    background-color: #003153;
    color: white;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1102;
    justify-content: center;
    align-items: center;
}

.modal.show {
    display: flex;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 50%;
    max-width: 400px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.modal-content h3 {
    text-align: center;
    color: #003153;
}

.stars {
    display: flex;
    justify-content: center;
    font-size: 24px;
    margin: 10px 0;
}

.stars i {
    cursor: pointer;
    margin: 0 5px;
    color: transparent;
    -webkit-text-stroke: 2px #003153;
}

.stars i.selected {
    color: #003153;
    -webkit-text-stroke: 0;
}

textarea {
    width: 95%;
    height: 100px;
    padding: 10px;
    border: 1px solid #003153;
    border-radius: 5px;
    margin: 10px 0;
    resize: vertical;
    font-size: 14px;
}

.modal-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.modal-button {
    flex: 1;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
}

.cancel-button {
    background-color: white;
    color: #003153;
    border: none;
    box-shadow: none;
    outline: none;
}

.cancel-button:hover,
.cancel-button:focus {
    box-shadow: none;
    border: none;
    outline: none;
    border: 2px solid #003153;
}

.confirm-button {
    background-color: #003153;
    color: white;
    border: none;
}

.no-bookings {
    background-color: #ffffff;
    border-radius: 8px;
    width: 100%;
    margin: 20px 10px 0 10px;
    padding: 15px;
    text-align: left;
    color: #003153;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
@media (max-width: 767px) {
    .booking-status-buttons {
        grid-template-columns: repeat(2, 1fr);
        gap: 6px;
        margin: 120px 10px 15px 10px;
    }
}

@media (max-width: 767px) {
    .booking-row {
        flex-direction: column;
    }

    .booking-entry {
        flex: 1 1 100%;
        width: 100%;
        min-width: unset;
    }

    .booking-info-container {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .booking-col-left {
        flex: 0 0 auto;
        text-align: center;
        margin-bottom: 10px;
    }

    .booking-col-labels,
    .booking-col-values {
        flex: none;
        text-align: left;
        font-weight: normal;
        display: inline-block;
        vertical-align: middle;
    }

    .booking-info-line {
        display: flex;
        gap: 6px;
        justify-content: center;
        width: 100%;
    }

    .booking-col-labels {
        font-weight: bold;
        min-width: 90px;
    }

    .booking-col-values {
        flex: 1;
        font-weight: bold;
        color: #003153;
    }

    .action-btn-container {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
        margin-top: 10px;
        width: 100%;
    }

    .action-btn-container button {
        width: 100%;
    }

    .tutor-image {
        width: 100px;
        height: 100px;
    }

    .modal-content {
        width: 90%;
        max-width: 350px;
    }

    textarea {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .booking-status-buttons {
        grid-template-columns: 1fr;
    }

    .status-btn {
        font-size: 11px;
        padding: 5px 3px;
    }

    .tutor-image {
        width: 80px;
        height: 80px;
    }

    .modal-buttons {
        flex-direction: column;
        gap: 8px;
    }
}

@media (max-width: 360px) {
    .booking-info-line {
        font-size: 13px;
        flex-direction: column;
    }
    .booking-info-label {
        min-width: 70px;
        margin-bottom: 2px;
    }
    .status-btn {
        font-size: 11px;
        padding: 6px 3px;
    }
    .action-btn-container {
        flex-wrap: wrap;
        justify-content: center;
    }
    .tutor-image {
        width: 50px;
        height: 50px;
    }
}

@media (min-width: 992px) {
    .booking-info-line {
        display: flex;
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
            <h3>WRTIE A REVIEW</h3>
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
        <?php
        $counter = 0;
        while ($row = $result->fetch_assoc()) :
            if ($counter % 2 == 0) {
                echo '<div class="booking-row">';
            }
        ?>
            <div class="booking-entry" data-booking-id="<?php echo $row['booking_id']; ?>" data-status="<?php echo strtolower($row['status']); ?>">
    
    <div class="booking-info-container">
        <div class="booking-col booking-col-left">
            <img src="../../tutor-icon.png" alt="Tutor Image" class="tutor-image">
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

        <?php
            $counter++;
            if ($counter % 2 == 0) {
                echo '</div>';
            }
        endwhile;
        if ($counter % 2 != 0) {
            echo '</div>';
        }
        ?>
    <?php else : ?>
        <p class="no-bookings">No bookings found.</p>
    <?php endif; ?>

    <script src="../LearnerJS/booking_history.js"></script>
    <script src="../../time-date-sidebar.js"></script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(() => console.log('Service Worker registered!'))
                .catch(err => console.error('Service Worker registration failed:', err));
        }
    </script> 
</body>
</html>
