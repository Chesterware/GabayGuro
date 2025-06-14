<?php
require_once '../AdminPHP/admin_name.php';
require_once '../AdminPHP/iskol4rx_tutors.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Verify Tutors</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003153">
    <link rel="icon" href="/GabayGuroLogo.png">
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../AdminCSS/index.css" />
    <link rel="stylesheet" href="../AdminCSS/tutor_verification.css" />
</head>
<body class="sidebar-collapsed">
    <div class="header-title">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
        </button>
        <div class="page-label">VERIFY TUTORS</div>
        <div class="datetime" id="datetime"></div>
    </div>

    <div class="sidebar">
        <h2 class="admin-name"><?php echo htmlspecialchars($adminFullName); ?></h2>
        <h3 class="sidebar-label">TAGAPAG GABAY</h3>

        <div class="separator"></div>

        <form action="admin_dashboard.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-tachometer-alt"></i>Dashboard</button>
        </form>
        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-check-circle"></i>Verify Tutors</button>
        </form>
        <form action="manage_users.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-users-cog"></i>Manage Users</button>
        </form>
        <form action="admin_profile.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-user-cog"></i>Admin Profile</button>
        </form>

        <div class="separator"></div>

        <form action="../../api/logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>
    
    <div class="status-buttons">
        <a href="#" class="active" data-status="for verification">FOR VERIFICATION</a> 
        <a href="#" data-status="verified">VERIFIED</a> 
        <a href="#" data-status="unverified">UNVERIFIED</a>
    </div>

    <?php foreach ($statuses as $status): ?>
        <div class="tutor-section" data-status="<?= htmlspecialchars($status) ?>" style="<?= $status === 'for verification' ? 'display:block;' : 'display:none;' ?>">
            <?php if (!empty($tutors_by_status[$status])): ?>
                <div class="tutor-grid">
                    <?php foreach ($tutors_by_status[$status] as $tutor_id => $tutor): ?>
                        <div class="tutor-card">
                            <div class="tutor-name">
                                <?= htmlspecialchars("{$tutor['first_name']} {$tutor['middle_initial']}. {$tutor['last_name']}") ?>
                            </div>
                            <div class="tutor-info"><span class="label">Educational Attainment:</span> <strong><?= htmlspecialchars($tutor['educational_attainment']) ?></strong></div>
                            <div class="tutor-info"><span class="label">Specializations:</span> <strong>
                                <?= !empty($tutor['specializations']) 
                                    ? htmlspecialchars(implode(', ', $tutor['specializations'])) 
                                    : 'N/A' ?></strong>
                            </div>
                            <div class="tutor-info"><span class="label">Years of Experience:</span> <strong><?= htmlspecialchars($tutor['years_of_experience']) ?> experience</strong></div>
                            <div class="tutor-info"><span class="label">Rate (Hourly):</span> <strong><?= number_format($tutor['rate_per_hour'], 2) ?></strong></div>
                            <div class="tutor-info"><span class="label">Rate (Session):</span> <strong><?= number_format($tutor['rate_per_session'], 2) ?></strong></div>
                            <div class="tutor-info"><span class="label">Joined:</span> <strong><?= date('m-d-Y', strtotime($tutor['created_at'])) ?></strong></div>

                            <div class="tutor-info">
                                <span class="label">Credentials:</span>
                                <a href="../AdminPHP/tutor_docs.php?tutor_id=<?= $tutor_id ?>&type=diploma" class="download-btn" target="_blank">Download Diploma</a>
                                <a href="../AdminPHP/tutor_docs.php?tutor_id=<?= $tutor_id ?>&type=certificate" class="download-btn" target="_blank">Download Certificates</a>
                            </div>

                            <?php if ($status === 'for verification'): ?>
                                <form class="verify-form" method="POST">
                                    <input type="hidden" name="tutor_id" value="<?= $tutor_id ?>">
                                    <button type="button" class="verify-btn" data-status="unverified">UNVERIFY</button>
                                    <button type="button" class="verify-btn" data-status="verified">VERIFY</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="tutor-card"><h4>No tutors found in this status.</h4></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <script src="../../time-date-sidebar.js"></script>
    <script src="../AdminJS/tutor_verification.js"></script>
    
    <script>
        if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(() => console.log('Service Worker registered!'))
            .catch(err => console.error('Service Worker registration failed:', err));
        }
    </script> 
</body>
</html>