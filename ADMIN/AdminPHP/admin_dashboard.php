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
    <link rel="stylesheet" href="../AdminCSS/adminProfile.css">
    <style>
        .input-wrapper {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            z-index: 2;
        }
        .password-edit-mode .toggle-password {
            color: #003153;
        }
    </style>
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
            <button type="submit" class="btn active"><i class="fas fa-user"></i>My Profile</button>
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

    <div class="profile-container">
        <!-- Header Form -->
        <form class="profile-header">
            <h2>My Profile</h2>
        </form>

        <form class="profile-form" action="api/updateProfile.php" method="POST" enctype="multipart/form-data">
            <div class="form-content">
                <div class="photo-container">
                    <div class="photo-placeholder" id="photo-placeholder">
                        <?php if ($profile_picture_url): ?>
                            <img src="<?php echo $profile_picture_url; ?>" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 5px;">
                        <?php else: ?>
                            <i class="fas fa-user-circle" style="font-size: 150px; color: #ccc;"></i>
                        <?php endif; ?>
                    </div>
                    <label class="photo-upload" for="file-input">
                        Click to upload photo
                        <input type="file" id="file-input" name="profile_photo" style="display: none;" accept="image/*">
                    </label>
                    
                    <div id="confirm-buttons" style="display: none; margin-top: 10px;">
                        <button type="button" id="cancelPhoto" class="cancel-photo">Cancel</button>
                        <button type="button" id="savePhoto" class="save-photo">Save</button>
                    </div>
                </div>

                <div class="form-fields">
                    <div class="name-container">
                        <div class="first-name">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" readonly required>
                        </div>

                        <div class="middle-initial">
                            <label for="middle_initial">Middle Initial</label>
                            <input type="text" id="middle_initial" name="middle_initial" value="<?php echo htmlspecialchars($middle_initial); ?>" readonly required maxlength="1">
                        </div>
                    </div>

                    <div class="last-name">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" readonly required>
                    </div>

                    <div class="email">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    </div>

                    <div class="password">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" value="••••••••" readonly>
                            <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="confirmPassword" style="display: none;">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirmPassword" name="confirmPassword">
                            <i class="fas fa-eye-slash toggle-password" id="toggleConfirmPassword"></i>
                        </div>
                    </div>

                    <button type="button" class="edit-btn" id="editProfile">EDIT</button>
                    <div class="cancelSave">
                        <button type="button" class="cancel-btn" id="cancelEdit" style="display: none;">CANCEL</button>
                        <button type="submit" class="save-btn" id="saveProfile" style="display: none;">SAVE</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(`toggle${fieldId.charAt(0).toUpperCase() + fieldId.slice(1)}`);
            
            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            } else {
                field.type = "password";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            }
        }

        // Edit profile functionality
        document.getElementById('editProfile').addEventListener('click', function() {
            // Enable editing of fields
            document.getElementById('first_name').readOnly = false;
            document.getElementById('middle_initial').readOnly = false;
            document.getElementById('last_name').readOnly = false;
            
            // Change password field to empty and editable
            const passwordField = document.getElementById('password');
            passwordField.readOnly = false;
            passwordField.value = '';
            passwordField.placeholder = 'Enter new password';
            
            // Show confirm password field
            document.querySelector('.confirmPassword').style.display = 'block';
            
            // Show cancel/save buttons
            document.getElementById('cancelEdit').style.display = 'inline-block';
            document.getElementById('saveProfile').style.display = 'inline-block';
            
            // Hide edit button
            this.style.display = 'none';
            
            // Add password-edit-mode class for styling
            document.querySelector('.password').classList.add('password-edit-mode');
        });

        // Cancel edit functionality
        document.getElementById('cancelEdit').addEventListener('click', function() {
            // Reset all fields to original values
            document.getElementById('first_name').readOnly = true;
            document.getElementById('middle_initial').readOnly = true;
            document.getElementById('last_name').readOnly = true;
            
            // Reset password field
            const passwordField = document.getElementById('password');
            passwordField.readOnly = true;
            passwordField.type = 'password';
            passwordField.value = '••••••••';
            passwordField.placeholder = '';
            
            // Hide confirm password field
            document.querySelector('.confirmPassword').style.display = 'none';
            
            // Reset toggle icons
            document.getElementById('togglePassword').classList.replace("fa-eye", "fa-eye-slash");
            document.getElementById('toggleConfirmPassword').classList.replace("fa-eye", "fa-eye-slash");
            
            // Show edit button, hide cancel/save
            document.getElementById('editProfile').style.display = 'inline-block';
            this.style.display = 'none';
            document.getElementById('saveProfile').style.display = 'none';
            
            // Remove password-edit-mode class
            document.querySelector('.password').classList.remove('password-edit-mode');
        });

        // Photo upload functionality
        document.getElementById('file-input').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-placeholder').innerHTML = 
                        `<img src="${e.target.result}" alt="Preview" style="width: 150px; height: 150px; border-radius: 5px;">`;
                    document.getElementById('confirm-buttons').style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Initialize password toggle event listeners
        document.getElementById('togglePassword').addEventListener('click', function() {
            if (!document.getElementById('password').readOnly) {
                togglePasswordVisibility('password');
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            togglePasswordVisibility('confirmPassword');
        });
    </script>
</body>
</html>