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
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../LearnerCSS/learner_profile.css">
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
        <!-- Left: Learner Info -->
        <div class="learner-info">
            <label>Learner Information</label>

            <div class="name-container">
                <input type="text" id="first-name" name="first-name" placeholder="First Name" readonly required>
                <input type="text" id="middle-name" name="middle-name" placeholder="Middle Name" readonly required>
            </div>

            <div class="name-container">
                <input type="text" id="last-name" name="last-name" placeholder="Last Name" readonly required>
                <input type="date" id="birthdate" name="birthdate" placeholder="Birthdate" readonly required>
            </div>

            <input type="text" id="school-affiliation" name="school-affiliation" placeholder="Enter School Affiliation" readonly required>

            <select id="grade-level" name="grade-level" disabled required>
                <option value="" disabled selected>Select Grade Level</option>
                <option value="grade-1">Grade 1</option>
                <option value="grade-2">Grade 2</option>
                <option value="grade-3">Grade 3</option>
            </select>

            <select id="strand" name="strand" disabled required>
                <option value="" disabled selected>Select Strand</option>
                <option value="strand-1">Strand 1</option>
                <option value="strand-2">Strand 2</option>
                <option value="strand-3">Strand 3</option>
            </select>

            <div class="edit-container">
                <button type="button" class="edit-button" id="editProfileInfo">EDIT</button>
            </div>
            <div class="cancelSave" style="display: none;">
                <button type="button" class="edit-button cancel-btn" id="cancelProfileEdit">CANCEL</button>
                <button type="submit" class="edit-button save-btn" id="saveProfileInfo">SAVE</button>
            </div>
        </div>

        <!-- Right: Email & Password -->
        <div class="learner-email-password">
            <label>Change Password</label>

            <input type="email" id="email" name="email" placeholder="Enter Email Address" readonly>

            <div class="input-wrapper">
                <input type="password" id="password" name="password" value="••••••••" readonly placeholder="Enter Password">
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
                <button type="submit" class="edit-button save-btn" id="savePassword">SAVE</button>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const header = document.querySelector('.header-title');
            const profileContainer = document.querySelector('.profile-container');

            sidebar.classList.toggle('closed');
            header.classList.toggle('full-width');
            profileContainer.classList.toggle('sidebar-open');
        }

        function updateDateTime() {
            const now = new Date();
            const date = now.toLocaleDateString();
            const time = now.toLocaleTimeString();
            document.getElementById('datetime').innerHTML = `${date} ${time}`;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();

        function toggleFields(fields, enable) {
            fields.forEach(field => {
                if (field.tagName === 'SELECT') {
                    field.disabled = !enable;
                    field.style.backgroundColor = enable ? 'transparent' : '#f0f0f0';
                } else {
                    field.readOnly = !enable;
                }
            });
        }

        const editProfileBtn = document.getElementById('editProfileInfo');
        const cancelProfileBtn = document.getElementById('cancelProfileEdit');
        const saveProfileBtn = document.getElementById('saveProfileInfo');
        const profileCancelSave = cancelProfileBtn.parentElement;

        const profileFields = [
            document.getElementById('first-name'),
            document.getElementById('middle-name'),
            document.getElementById('last-name'),
            document.getElementById('birthdate'),
            document.getElementById('school-affiliation'),
            document.getElementById('grade-level'),
            document.getElementById('strand')
        ];

        let profileBackup = {};

        editProfileBtn.addEventListener('click', () => {
            toggleFields(profileFields, true);
            profileCancelSave.style.display = 'flex';
            editProfileBtn.style.display = 'none';

            profileFields.forEach(field => profileBackup[field.id] = field.value);
        });

        cancelProfileBtn.addEventListener('click', () => {
            toggleFields(profileFields, false);
            profileCancelSave.style.display = 'none';
            editProfileBtn.style.display = 'block';

            profileFields.forEach(field => field.value = profileBackup[field.id]);
        });

        saveProfileBtn.addEventListener('click', () => {
            toggleFields(profileFields, false);
            profileCancelSave.style.display = 'none';
            editProfileBtn.style.display = 'block';
        });

        const editPasswordBtn = document.getElementById('editPassword');
        const cancelPasswordBtn = document.getElementById('cancelPasswordEdit');
        const savePasswordBtn = document.getElementById('savePassword');
        const passwordCancelSave = cancelPasswordBtn.parentElement;

        const emailField = document.getElementById('email');
        const passwordField = document.getElementById('password');
        const confirmPasswordWrapper = document.querySelector('.confirmPassword');
        const confirmPasswordField = document.getElementById('confirmPassword');

        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

        let passwordBackup = {
            email: '',
            password: ''
        };

        editPasswordBtn.addEventListener('click', () => {
            emailField.readOnly = true;
            passwordField.readOnly = false;
            confirmPasswordWrapper.style.display = 'block';
            passwordCancelSave.style.display = 'flex';
            editPasswordBtn.style.display = 'none';

            passwordField.value = '';
            confirmPasswordField.value = '';

            togglePassword.classList.remove('hidden');
            toggleConfirmPassword.classList.remove('hidden');

            passwordBackup.email = emailField.value;
            passwordBackup.password = passwordField.value;
        });

        cancelPasswordBtn.addEventListener('click', () => {
            emailField.readOnly = true;
            passwordField.readOnly = true;
            confirmPasswordWrapper.style.display = 'none';
            passwordCancelSave.style.display = 'none';
            editPasswordBtn.style.display = 'block';

            emailField.value = passwordBackup.email;
            passwordField.value = passwordBackup.password;
            confirmPasswordField.value = '';

            togglePassword.classList.add('hidden');
            toggleConfirmPassword.classList.add('hidden');

            passwordField.type = 'password';
            confirmPasswordField.type = 'password';
        });

        savePasswordBtn.addEventListener('click', () => {
            emailField.readOnly = true;
            passwordField.readOnly = true;
            confirmPasswordWrapper.style.display = 'none';
            passwordCancelSave.style.display = 'none';
            editPasswordBtn.style.display = 'block';

            togglePassword.classList.add('hidden');
            toggleConfirmPassword.classList.add('hidden');

            passwordField.type = 'password';
            confirmPasswordField.type = 'password';
        });

        function syncTogglePasswordVisibility() {
            const isVisible = passwordField.type === 'text' || confirmPasswordField.type === 'text';
            const newType = isVisible ? 'password' : 'text';

            passwordField.type = newType;
            confirmPasswordField.type = newType;

            [togglePassword, toggleConfirmPassword].forEach(icon => {
                icon.classList.toggle('fa-eye', newType === 'text');
                icon.classList.toggle('fa-eye-slash', newType === 'password');
            });
        }

        togglePassword.addEventListener('click', syncTogglePasswordVisibility);
        toggleConfirmPassword.addEventListener('click', syncTogglePasswordVisibility);
    </script>
</body>
</html>