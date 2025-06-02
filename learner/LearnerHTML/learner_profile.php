<?php
require_once '../LearnerPHP/learner_details.php';

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
    <title>My Profile</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003153">
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../LearnerCSS/learner_profile.css">
    <link rel="stylesheet" href="../LearnerCSS/index.css">
</head>
<body>
    <div class="header-title">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="page-label">MY PROFILE</div>
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
        <form action="booking_history.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-history"></i>Booking History</button>
        </form>
        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-user-cog"></i>My Profile</button>
        </form>

        <div class="separator"></div>

        <form action="../../api/logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>

    <div class="profile-container sidebar-open">
        <div class="learner-info">
            <label>Learner Information</label>

            <div class="name-container">
                <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($learnerData['first_name'] ?? ''); ?>" placeholder="First Name" readonly required>
                <input type="text" id="middle-name" name="middle-name" value="<?php echo htmlspecialchars($learnerData['middle_initial'] ?? ''); ?>" placeholder="Middle Initial" readonly maxlength="1">
            </div>

            <div class="name-container">
                <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($learnerData['last_name'] ?? ''); ?>" placeholder="Last Name" readonly required>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($learnerData['birthdate'] ?? ''); ?>" placeholder="Birthdate" readonly required>
            </div>

            <input type="text" id="school-affiliation" name="school-affiliation" value="<?php echo htmlspecialchars($learnerData['school_affiliation'] ?? ''); ?>" placeholder="Enter School Affiliation" readonly required>

            <select id="grade-level" name="grade-level" disabled required>
                <option value="N/A" <?= ($learnerData['grade_level'] ?? '') === 'N/A' ? 'selected' : '' ?>>Not Applicable</option>
                <option value="G7" <?= ($learnerData['grade_level'] ?? '') === 'G7' ? 'selected' : '' ?>>Grade 7</option>
                <option value="G8" <?= ($learnerData['grade_level'] ?? '') === 'G8' ? 'selected' : '' ?>>Grade 8</option>
                <option value="G9" <?= ($learnerData['grade_level'] ?? '') === 'G9' ? 'selected' : '' ?>>Grade 9</option>
                <option value="G10" <?= ($learnerData['grade_level'] ?? '') === 'G10' ? 'selected' : '' ?>>Grade 10</option>
                <option value="G11" <?= ($learnerData['grade_level'] ?? '') === 'G11' ? 'selected' : '' ?>>Grade 11</option>
                <option value="G12" <?= ($learnerData['grade_level'] ?? '') === 'G12' ? 'selected' : '' ?>>Grade 12</option>
            </select>

            <select id="strand" name="strand" disabled required>
                <option value="N/A" <?= ($learnerData['strand'] ?? '') === 'N/A' ? 'selected' : '' ?>>Not Applicable</option>
                <option value="STEM" <?= ($learnerData['strand'] ?? '') === 'STEM' ? 'selected' : '' ?>>STEM</option>
                <option value="ABM" <?= ($learnerData['strand'] ?? '') === 'ABM' ? 'selected' : '' ?>>ABM</option>
                <option value="HUMSS" <?= ($learnerData['strand'] ?? '') === 'HUMSS' ? 'selected' : '' ?>>HUMSS</option>
                <option value="GAS" <?= ($learnerData['strand'] ?? '') === 'GAS' ? 'selected' : '' ?>>GAS</option>
            </select>
            <div class="edit-container">
                <button type="button" class="edit-button" id="editProfileInfo">EDIT</button>
            </div>
            <div class="cancelSave" style="display: none;">
                <button type="button" class="edit-button cancel-btn" id="cancelProfileEdit">CANCEL</button>
                <button type="button" class="edit-button save-btn" id="saveProfileInfo">SAVE</button>
            </div>
        </div>

        <div class="learner-email-password">
            <label>Change Password</label>

            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($learnerData['email'] ?? ''); ?>" placeholder="Enter Email Address" readonly>

            <div class="input-wrapper" id="current-password-wrapper" style="display: none;">
                <input type="password" id="current-password" name="current_password" placeholder="Current Password">
                <i class="fas fa-eye-slash toggle-password hidden" id="toggleCurrentPassword"></i>
            </div>

            <div class="input-wrapper">
                <input type="password" id="password" name="password" value="••••••••" readonly placeholder="New Password">
                <i class="fas fa-eye-slash toggle-password hidden" id="togglePassword"></i>
            </div>

            <div class="confirmPassword" style="display: none;">
                <div class="input-wrapper">
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                    <i class="fas fa-eye-slash toggle-password hidden" id="toggleConfirmPassword"></i>
                </div>
            </div>

            <div class="edit-container">
                <button type="button" class="edit-button" id="editPassword">EDIT</button>
            </div>
            <div class="cancelSave" style="display: none;">
                <button type="button" class="edit-button cancel-btn" id="cancelPasswordEdit">CANCEL</button>
                <button type="button" class="edit-button save-btn" id="savePassword">SAVE</button>
            </div>
        </div>
    </div>

    <script src="../LearnerJS/learner_profile.js"></script>
</body>
</html>