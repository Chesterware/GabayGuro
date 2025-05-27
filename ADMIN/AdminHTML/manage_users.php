<?php
require_once '../AdminPHP/admin_name.php'; 
require_once '../AdminPHP/iskol4rx_users.php';
require_once '../AdminPHP/auth_admin.php';
require_once '../AdminPHP/add_admin.php';

if (isset($_SESSION['admin_errors'])) {
    foreach ($_SESSION['admin_errors'] as $error) {
        echo "<script>alert('". addslashes($error) ."');</script>";
    }
    unset($_SESSION['admin_errors']);
}

if (isset($_SESSION['admin_success'])) {
    echo "<script>alert('". addslashes($_SESSION['admin_success']) ."');</script>";
    unset($_SESSION['admin_success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Users</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003153">
    <link rel="icon" href="/GabayGuroLogo.png">
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
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
            <button type="submit" class="btn"><i class="fas fa-check-circle"></i>Verify Tutors</button>
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
                    <button class="add-btn">
                        <i class="fas fa-plus-circle"></i>
                    </button>
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
                        <?php foreach ($tutors as $index => $tutor): ?>
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

                                <form method="POST" action="../AdminPHP/update_tutor.php" id="edit-form-<?= $index ?>">
                                    <input type="hidden" name="tutor_id" value="<?= $tutor['tutor_id'] ?>">

                                    <p><span class="label">Status:</span>
                                        <select name="status" id="status-<?= $index ?>" disabled required>
                                            <option value="For Verification" <?= $tutor['status'] === 'For Verification' ? 'selected' : '' ?>>For Verification</option>
                                            <option value="Verified" <?= $tutor['status'] === 'Verified' ? 'selected' : '' ?>>Verified</option>
                                            <option value="Unverified" <?= $tutor['status'] === 'Unverified' ? 'selected' : '' ?>>Unverified</option>
                                        </select>
                                    </p>

                                    <div class="button-group">
                                        <button type="button" class="cancel-btn" id="cancel-btn-<?= $index ?>" onclick="cancelEdit(<?= $index ?>)" style="display:none;">CANCEL</button>
                                        <button type="submit" class="update-btn" id="update-btn-<?= $index ?>" style="display:none;">UPDATE</button>
                                        <button type="submit" class="delete-btn" id="delete-btn-<?= $index ?>" title="Delete Tutor" form="delete-form-<?= $index ?>">DELETE</button>
                                                                    <button type="button" class="edit-btn" id="edit-btn-<?= $index ?>" onclick="enableEdit(<?= $index ?>)">EDIT</button>
                                    </div>
                
                                </form>

                                <form id="delete-form-<?= $index ?>" method="POST" action="../AdminPHP/delete_user.php" onsubmit="return confirm('Are you sure you want to delete this tutor?');" style="display:none;">
                                    <input type="hidden" name="user_type" value="tutor">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($tutor['tutor_id']); ?>">
                                </form>

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
                                
                                <form method="POST" action="../AdminPHP/delete_user.php" onsubmit="return confirm('Are you sure you want to delete this learner?');" style="display:inline;">
                                    <input type="hidden" name="user_type" value="learner">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($learner['learner_id']); ?>">
                                    <button type="submit" class="delete-btn" title="Delete Tutor">DELETE</button>
                                </form>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No learners found.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <div id="adminModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Create Admin Account</h3>
                <form id="adminForm" method="POST" action="">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="middle_initial" placeholder="Middle Initial" maxlength="1">
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="add_admin">ADD ADMIN</button>
                </form>
        </div>
    </div>

    <script src="../AdminJS/manage_users.js"></script>
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