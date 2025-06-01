<?php
require_once '../AdminPHP/admin_name.php';
require_once '../AdminPHP/auth_admin.php';
require_once '../AdminPHP/admin_profile.php';

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
    <title>Admin Profile</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003153">
    <link rel="icon" href="/GabayGuroLogo.png">
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../AdminCSS/index.css" />
    <link rel="stylesheet" href="../AdminCSS/admin_profile.css" />
</head>
<body class="sidebar-collapsed">
    <div class="header-title">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
        </button>
        <div class="page-label">ADMIN PROFILE</div>
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
        <form action="manage_users.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-users-cog"></i>Manage Users</button>
        </form>
        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-user-cog"></i>Admin Profile</button>
        </form>

        <div class="separator"></div>

        <form action="../../api/logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>

    <main class="content-container">
        <?php if ($error_msg): ?>
            <div class="alert alert-danger" id="errorAlert"><?php echo htmlspecialchars($error_msg); ?></div>
        <?php endif; ?>
        
        <?php if ($success_msg): ?>
            <div class="alert alert-success" id="successAlert"><?php echo htmlspecialchars($success_msg); ?></div>
        <?php endif; ?>
        
        <div class="profile-container">
            <section class="profile-section">
                <h2>Personal Information</h2>
                <form id="profile-form" method="POST" action="">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($admin['first_name']); ?>" disabled required>
                    </div>
                    
                    <div class="form-group">
                        <label for="middle_initial">Middle Initial</label>
                        <input type="text" id="middle_initial" name="middle_initial" value="<?php echo htmlspecialchars($admin['middle_initial']); ?>" maxlength="5" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($admin['last_name']); ?>" disabled required>
                    </div>

                    <div class="profile-actions">
                        <button type="button" id="edit-profile-btn" class="edit-btn">EDIT</button>
                        <button type="button" id="cancel-profile-btn" class="cancel-btn" style="display:none;">CANCEL</button>
                        <button type="submit" id="update-profile-btn" name="update_profile" class="update-btn" style="display:none;">UPDATE</button>
                    </div>
                </form>
            </section>
            
            <section class="profile-section">
                <h2>Account Information</h2>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="readonly-field"><?php echo htmlspecialchars($admin['email']); ?></div>
                </div>
                
                <form id="password-form" method="POST" action="">
                    <h3>Change Password</h3>
                    
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="profile-actions">
                        <button type="submit" name="update_password" class="update-btn">UPDATE PASSWORD</button>
                    </div>
                </form>
            </section>
        </div>
    </main>

    <script src="../AdminJS/admin_profile.js"></script>
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