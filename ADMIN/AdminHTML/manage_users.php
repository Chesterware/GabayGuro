<?php
require_once '../AdminPHP/admin_name.php'; 
require_once '../AdminPHP/iskol4rx_users.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Users</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../AdminCSS/index.css" />
    <link rel="stylesheet" href="../AdminCSS/manage_users.css" />
</head>
<body class="sidebar-collapsed">
    <div class="header-title">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="page-label">MANAGE USERS</div>
        <div class="datetime" id="datetime"></div>
    </div>

    <div class="sidebar">
        <h2 class="admin-name"><?php echo htmlspecialchars($adminFullName); ?></h2>
        <h3 class="sidebar-label">TAGAPAG GABAY</h3>

        <div class="separator"></div>

        <form action="admin_dashboard.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-tachometer-alt"></i>Dashboard</button>
        </form>
        <form action="tutor_verification.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-check-circle"></i>Tutor Verification</button>
        </form>
        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-users-cog"></i>Manage Users</button>
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

    <main class="content-container">
        <div class="user-columns">
            <section class="user-column">
                <div class="column-header">
                    <h2>ADMINS (<?php echo count($admins); ?>)</h2>
                </div>
                <div class="scrollable-list">
                    <?php if (!empty($admins)): ?>
                        <?php foreach ($admins as $admin): ?>
                            <div class="user-card">
                                <h3>
                                    <?php 
                                        echo htmlspecialchars(
                                            $admin['first_name'] . ' ' . 
                                            ($admin['middle_initial'] ? $admin['middle_initial'] . ' ' : '') . 
                                            $admin['last_name']
                                        );
                                    ?>
                                </h3>
                               <p><span class="label">Admin ID:</span> <strong><?php echo htmlspecialchars($admin['admin_id']); ?></strong></p>
                                <p><span class="label">Joined:</span> <strong>
                                    <?php 
                                        $date = new DateTime($admin['created_at']);
                                        echo $date->format('m-d-Y'); 
                                    ?>
                                </strong></p>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No admins found.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="user-column">
                <div class="column-header">
                    <h2>TUTORS (<?php echo count($tutors); ?>)</h2>
                </div>
                <div class="scrollable-list">
                    <?php if (!empty($tutors)): ?>
                        <?php foreach ($tutors as $tutor): ?>
                            <div class="user-card">
                                <h3>
                                    <?php 
                                        echo htmlspecialchars(
                                            $tutor['first_name'] . ' ' . 
                                            ($tutor['middle_initial'] ? $tutor['middle_initial'] . '. ' : '') . 
                                            $tutor['last_name']
                                        );
                                    ?>
                                </h3>
                                <p><span class="label">Tutor ID:</span> <strong><?php echo htmlspecialchars($tutor['tutor_id']); ?></strong></p>
                                <p><span class="label">Joined:</span> <strong>
                                    <?php 
                                        $date = new DateTime($tutor['created_at']);
                                        echo $date->format('m-d-Y'); 
                                    ?>
                                </strong></p>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No tutors found.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="user-column">
                <div class="column-header">
                    <h2>LEARNERS (<?php echo count($learners); ?>)</h2>
                </div>
                <div class="scrollable-list">
                    <?php if (!empty($learners)): ?>
                        <?php foreach ($learners as $learner): ?>
                            <div class="user-card">
                                <h3>
                                    <?php 
                                        echo htmlspecialchars(
                                            $learner['first_name'] . ' ' . 
                                            ($learner['middle_initial'] ? $learner['middle_initial'] . '. ' : '') . 
                                            $learner['last_name']
                                        );
                                    ?>
                                </h3>
                                <p><span class="label">Learner ID:</span> <strong><?php echo htmlspecialchars($learner['learner_id']); ?></strong></p>
                                <p><span class="label">Joined:</span><strong>
                                    <?php 
                                        $date = new DateTime($learner['created_at']);
                                        echo $date->format('m-d-Y'); 
                                    ?>
                                </strong></p>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No learners found.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle("sidebar-collapsed");
        }
    </script>
    <script src="../AdminJS/time_date.js"></script>
</body>
</html>