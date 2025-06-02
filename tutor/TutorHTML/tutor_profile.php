<?php
require_once '../TutorPHP/tutor_details.php';

if (!isset($_SESSION['tutor_id'])) {
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
    <link rel="stylesheet" href="../TutorCSS/tutor_profile.css">
    <link rel="stylesheet" href="../TutorCSS/index.css">
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
        <h2 class="logged-in-tutor"><?php echo htmlspecialchars($tutorData['full_name']); ?></h2>
        <h3 class="sidebar-label">TUTOR</h3>

        <div class="separator"></div>

        <form action="ratings_review.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-star"></i>Ratings / Review</button>
        </form>
        <form action="tutor_booking.php" method="GET">
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
        <div class="tutor-info">
            <label>Tutor Information</label>

            <div class="name-container">
                <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($tutorData['first_name'] ?? ''); ?>" placeholder="First Name" readonly required>
                <input type="text" id="middle-name" name="middle-name" value="<?php echo htmlspecialchars($tutorData['middle_initial'] ?? ''); ?>" placeholder="Middle Initial" readonly maxlength="1">
            </div>

            <div class="name-container">
                <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($tutorData['last_name'] ?? ''); ?>" placeholder="Last Name" readonly required>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($tutorData['birthdate'] ?? ''); ?>" placeholder="Birthdate" readonly required>
            </div>

            <select id="educational-attainment" name="educational-attainment" disabled required>
                <option value="">Select Educational Attainment</option>
                <option value="Junior High School Graduate" <?= ($tutorData['educational_attainment'] ?? '') === 'Junior High School Graduate' ? 'selected' : '' ?>>Junior High School Graduate</option>
                <option value="Senior High School Graduate" <?= ($tutorData['educational_attainment'] ?? '') === 'Senior High School Graduate' ? 'selected' : '' ?>>Senior High School Graduate</option>
                <option value="College Undergraduate" <?= ($tutorData['educational_attainment'] ?? '') === 'College Undergraduate' ? 'selected' : '' ?>>College Undergraduate</option>
                <option value="Associate's Degree" <?= ($tutorData['educational_attainment'] ?? '') === 'Associate\'s Degree' ? 'selected' : '' ?>>Associate's Degree</option>
                <option value="Bachelor's Degree" <?= ($tutorData['educational_attainment'] ?? '') === 'Bachelor\'s Degree' ? 'selected' : '' ?>>Bachelor's Degree</option>
                <option value="Master's Degree" <?= ($tutorData['educational_attainment'] ?? '') === 'Master\'s Degree' ? 'selected' : '' ?>>Master's Degree</option>
                <option value="Doctoral Degree" <?= ($tutorData['educational_attainment'] ?? '') === 'Doctoral Degree' ? 'selected' : '' ?>>Doctoral Degree</option>
            </select>

            <select id="years-experience" name="years-experience" disabled required>
                <option value="">Select Years of Experience</option>
                <option value="Less than 1 year" <?= ($tutorData['years_of_experience'] ?? '') === 'Less than 1 year' ? 'selected' : '' ?>>Less than 1 year</option>
                <option value="1-3 years" <?= ($tutorData['years_of_experience'] ?? '') === '1-3 years' ? 'selected' : '' ?>>1-3 years</option>
                <option value="4-6 years" <?= ($tutorData['years_of_experience'] ?? '') === '4-6 years' ? 'selected' : '' ?>>4-6 years</option>
                <option value="7+ years" <?= ($tutorData['years_of_experience'] ?? '') === '7+ years' ? 'selected' : '' ?>>7+ years</option>
            </select>

            <div class="edit-container">
                <button type="button" class="edit-button" id="editProfileInfo">EDIT</button>
            </div>
            <div class="cancelSave" style="display: none;">
                <button type="button" class="edit-button cancel-btn" id="cancelProfileEdit">CANCEL</button>
                <button type="button" class="edit-button save-btn" id="saveProfileInfo">SAVE</button>
            </div>
        </div>

        <div class="tutor-email-password">
    <label>Change Password</label>

    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($tutorData['email'] ?? ''); ?>" placeholder="Enter Email Address" readonly>

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

    <script src="../TutorJS/tutor_profile.js"></script>
</body>
</html>