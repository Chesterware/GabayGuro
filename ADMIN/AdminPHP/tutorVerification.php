<?php
require_once __DIR__ . '/../../api/db_connection.php';
require_once __DIR__ . '/../../api/getUserDetails.php';
require_once __DIR__ . '/../AdminAPI/verifyTutors.php';

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
    <!--<meta http-equiv="refresh" content="1">-->
    <title>My Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../AdminCSS/tutorVerification.css">
</head>

<style>
</style>

<body>
    <div class="sidebar">
        <h2 class="admin-name">
            <?php
            echo $_SESSION['first_name'] . ' ' . $_SESSION['middle_initial'] . ' ' . $_SESSION['last_name']; 
            ?>
        </h2>
        
        <h3 class="sidebar-label">TAGAPAG-GABAY</h3>
        <div class="separator"></div>

        <form action="tutorVerification.php" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-check-circle"></i>Tutor Verification</button>
        </form>

        <form action="admin_dashboard.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-user"></i>My Profile</button>
        </form>

        <form action="userActivities.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-clipboard-list"></i>User Activities</button>
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

    <div class="forVerification">
        <a href="tutorVerification.php?status=Pending" class="btn <?php echo ($status == 'Pending') ? 'active' : ''; ?>">PENDING</a>
        <a href="tutorVerification.php?status=Verified" class="btn <?php echo ($status == 'Verified') ? 'active' : ''; ?>">VERIFIED</a>
        <a href="tutorVerification.php?status=Not Verified" class="btn <?php echo ($status == 'Not Verified') ? 'active' : ''; ?>">NOT VERIFIED</a>
    </div>

    <!-- Table for Tutors -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Educational Attainment</th>
                <th>Uploaded Diploma</th>
                <th>Uploaded Other Credentials</th>
                <?php if ($status !== 'Verified' && $status !== 'Not Verified') { ?>
                    <th>Actions</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tutors)) { ?>
                <?php foreach ($tutors as $tutor) { ?>
                    <tr>
                        <td><?php echo $tutor['first_name'] . ' ' . $tutor['middle_initial'] . '.' . $tutor['last_name']; ?></td>
                        <td><?php echo $tutor['educational_attainment']; ?></td>
                        <td><?php echo $tutor['diploma']; ?></td>
                        <td><?php echo $tutor['other_certificates']; ?></td>
                        <?php if ($status !== 'Verified' && $status !== 'Not Verified') { ?>
                            <td>
                                <form method="POST">
                                    <?php if ($tutor['status'] === 'Pending') { ?>
                                        <button type="submit" name="verify_tutor_id" value="<?php echo $tutor['tutor_id']; ?>" class="btn verify-btn">Verify</button>
                                    <?php } ?>
                                    <?php if ($tutor['status'] !== 'Verified' && $tutor['status'] !== 'Not Verified') { ?>
                                        <button type="submit" name="unverify_tutor_id" value="<?php echo $tutor['tutor_id']; ?>" class="btn unverify-btn">Unverify</button>
                                    <?php } ?>
                                </form>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5">No tutors found with the selected status.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.forVerification .btn');

            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    buttons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });

                    button.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>