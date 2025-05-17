<?php
require_once __DIR__ . '/../../api/db_connection.php';
require_once __DIR__ . '/../../api/getUserDetails.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Verification</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../AdminCSS/tutorVerification.css">
</head>
<body>
    <div class="sidebar">
        <h2 class="admin-name">
            <?php echo $first_name . ' ' . $middle_initial . ' ' . $last_name; ?>
        </h2>
        
        <h3 class="sidebar-label">TAGAPAG-GABAY</h3>
        <div class="separator"></div>

        
        <form action="tutorVerification.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-check-circle"></i>Tutor Verification</button>
        </form>

        <form action="admin_dashboard.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-user"></i>My Profile</button>
        </form>

        <form action="userActivities.php" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-clipboard-list"></i>User Activities</button>
        </form>

        <form action="manageUser.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-users-cog"></i>Manage User</button>
        </form>

        <div class="separator"></div>

        <form action="/iskol4rx/api/logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>
</body>
</html>

