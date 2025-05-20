<?php
require_once '../LearnerPHP/learner_details.php';
require_once '../LearnerPHP/notifications.php';

if (!isset($_SESSION['learner_id'])) {
    header("Location: ../../index.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../LearnerCSS/notifications.css">
</head>
<body>
    <div class="header-title">  
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="page-label">NOTIFICATIONS</div>
        <div class="datetime" id="datetime"></div>
    </div>

    <div class="sidebar">
        <h2 class="learner-name"><?php echo htmlspecialchars($learnerData['full_name']); ?></h2>
        <h3 class="sidebar-label">LEARNER</h3>

        <div class="separator"></div>

        <form action="find_tutors.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-search"></i>Find Tutors</button>
        </form>
        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-bell"></i>Notifications</button>
        </form>
        <form action="booking_history.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-history"></i>Booking History</button>
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

    <a href="#" id="delete-all" onclick="showDeleteAllModal()">DELETE ALL</a>

    <div class="notifications-container full-width">
        <?php 
        if (empty($notifications)) { 
        ?>
            <div class="classnotification">
                <div class="notification-content">
                    <p style="color: #003153; font-weight: bold;">No notifications available.</p>
                </div>
            </div>
        <?php 
        } else {
            foreach ($notifications as $row) {
                $tutor_name = htmlspecialchars($row['tutor_name']);
                $status = $row['display_status'];
                $status_upper = strtoupper($status);

                if (strtolower($row['status']) === 'cancelled') {
                    $message = "<span style=\"color: #003153; font-weight: bold;\">$status_upper</span> - You have " . strtolower($status) . " your booking with $tutor_name";
                } else {
                    $message = "<span style=\"color: #003153; font-weight: bold;\">$status_upper</span> - $tutor_name has " . strtolower($status) . " your booking";
                }
            ?>
                <div class="classnotification">
                    <div class="notification-content">
                        <p><?php echo $message; ?></p>
                        <small><?php echo htmlspecialchars($row['time_ago']); ?></small>
                    </div>

                    <button class="notification-delete-btn" onclick="showDeleteNotificationModal(<?php echo $row['booking_id']; ?>)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
        <?php 
            }
        } 
        ?>
    </div>

    <div class="modal" id="deleteNotificationModal">
        <div class="modal-content">
            <p>Do you want to delete this notification?</p>
            <div class="modal-buttons">
                <button class="cancel-btn" onclick="closeNotificationModal()">CANCEL</button>
                <button class="modal-delete-btn" id="deleteOne" onclick="deleteNotification()">DELETE</button>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteAllModal">
        <div class="modal-content">
            <p>Do you want to delete all notifications?</p>
            <div class="modal-buttons">
                <button class="cancel-btn" onclick="closeAllModal()">CANCEL</button>
                <button class="modal-delete-btn" id="deleteAll" onclick="deleteAllNotifications()">DELETE</button>
            </div>
        </div>
    </div>

    <script src="../LearnerJS/notifications.js"></script>
</body>
</html>