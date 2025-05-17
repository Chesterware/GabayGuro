<?php

require_once __DIR__ . '/../../api/db_connection.php';
require_once __DIR__ . '/../../api/getUserDetails.php';
require_once __DIR__ . '/../AdminAPI/addUsers.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: /iskol4rx/login.php"); // Added full path
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addUser'])) {
    $errors = [];
    $requiredFields = ['firstName', 'lastName', 'email', 'password', 'confirmPassword'];
    
    // Validate required fields
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = "This field is required";
        }
    }

    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address";
    }

    // Validate password match
    if ($_POST['password'] !== $_POST['confirmPassword']) {
        $errors['confirmPassword'] = "Passwords do not match";
    }

    // Validate password strength
    if (strlen($_POST['password']) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    }

    if (empty($errors)) {
        // Your existing successful registration code here
        $_SESSION['successMessage'] = "New user added successfully!";
        header("Location: manageUser.php");
        exit();
    } else {
        $_SESSION['formErrors'] = $errors;
        $_SESSION['formData'] = $_POST;
        header("Location: manageUser.php");
        exit();
    }
}
// Fetch all admin users from database
$admin_query = "SELECT * FROM admin";
$admin_result = $conn->query($admin_query);
$admins = [];
if ($admin_result->num_rows > 0) {
    while($row = $admin_result->fetch_assoc()) {
        $admins[] = $row;
    }
}

// Fetch all tutors from database
$tutor_query = "SELECT * FROM tutor";
$tutor_result = $conn->query($tutor_query);
$tutors = [];
if ($tutor_result->num_rows > 0) {
    while($row = $tutor_result->fetch_assoc()) {
        $tutors[] = $row;
    }
}

// Fetch all learners from database
$learner_query = "SELECT * FROM learner";
$learner_result = $conn->query($learner_query);
$learners = [];
if ($learner_result->num_rows > 0) {
    while($row = $learner_result->fetch_assoc()) {
        $learners[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../AdminCSS/manageUsers.css">
    <style>
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 50px;
            margin-top: 20px;
        }
        
        .user-card {
            background: white;
            border: 2px solid #003153;
            border-radius: 10px;
            padding: 20px;
            color: #333;
            box-shadow: 0 2px 5px rgba(0,0,0,0.7);
            transition: all 0.3s ease;
        }
        
        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.7);
        }
        
        .user-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #003153;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
            border: 2px solid #002440;
        }
        
        .user-name {
            font-weight: bold;
            color: #003153;
        }
        
        .user-email {
            color: #666;
            font-size: 0.9em;
        }
        
        .user-details div {
            color: #333;
        }
        
        .user-details strong {
            color: #003153;
        }

        /* New styles for user type sections */
        .user-type-section {
            margin-bottom: 40px;
        }
        
        .user-type-title {
            color: #003153;
            font-size: 1.5rem;
            margin-bottom: 15px;
            padding-left: 50px;
        }
    </style>
</head>
<body>
    <!-- Your existing sidebar code remains exactly the same -->
    <div class="sidebar">
        <h2 class="admin-name">
            <?php
            echo $_SESSION['first_name'] . ' ' . $_SESSION['middle_initial'] . '<br>' . $_SESSION['last_name']; 
            ?>
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
            <button type="submit" class="btn"><i class="fas fa-clipboard-list"></i>User Activities</button>
        </form>

        <form action="manageUser.php" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-users-cog"></i>Manage User</button>
        </form>

        <div class="separator"></div>

        <form action="/iskol4rx/api/logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>

    <!-- Your existing header and add user button remain the same -->
    <div class="header-container">
        <div class="header-btn">
            <h1 class="label-top">Manage Users</h1>
        </div>
        <button type="button" class="header-btn" id="addUserBtn"><i class="fas fa-circle-plus"></i> Add Users</button>
    </div>

    <!-- Updated user display sections -->
    <div class="user-type-section">
        <h2 class="user-type-title"><i class="fas fa-user-shield"></i> Administrators</h2>
        <div class="user-grid">
            <?php foreach ($admins as $admin): ?>
            <div class="user-card">
                <div class="user-header">
                    <div class="user-avatar">
                        <?php echo substr($admin['first_name'], 0, 1) . substr($admin['last_name'], 0, 1); ?>
                    </div>
                    <div>
                        <div class="user-name"><?php echo $admin['first_name'] . ' ' . $admin['middle_initial'] . ' ' . $admin['last_name']; ?></div>
                        <div class="user-email"><?php echo $admin['email']; ?></div>
                    </div>
                </div>
                <div class="user-details">
                    <div><strong>Admin ID:</strong> <?php echo $admin['admin_id']; ?></div>
                    <div><strong>Created:</strong> <?php echo date('M d, Y', strtotime($admin['created_at'])); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="user-type-section">
        <h2 class="user-type-title"><i class="fas fa-chalkboard-teacher"></i> Tutors</h2>
        <div class="user-grid">
            <?php foreach ($tutors as $tutor): ?>
            <div class="user-card">
                <div class="user-header">
                    <div class="user-avatar">
                        <?php echo substr($tutor['first_name'], 0, 1) . substr($tutor['last_name'], 0, 1); ?>
                    </div>
                    <div>
                        <div class="user-name"><?php echo $tutor['first_name'] . ' ' . $tutor['middle_initial'] . ' ' . $tutor['last_name']; ?></div>
                        <div class="user-email"><?php echo $tutor['email']; ?></div>
                    </div>
                </div>
                <div class="user-details">
                    <div><strong>Tutor ID:</strong> <?php echo $tutor['tutor_id']; ?></div>
                    <div><strong>Created:</strong> <?php echo date('M d, Y', strtotime($tutor['created_at'])); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="user-type-section">
        <h2 class="user-type-title"><i class="fas fa-user-graduate"></i> Learners</h2>
        <div class="user-grid">
            <?php foreach ($learners as $learner): ?>
            <div class="user-card">
                <div class="user-header">
                    <div class="user-avatar">
                        <?php echo substr($learner['first_name'], 0, 1) . substr($learner['last_name'], 0, 1); ?>
                    </div>
                    <div>
                        <div class="user-name"><?php echo $learner['first_name'] . ' ' . $learner['middle_initial'] . ' ' . $learner['last_name']; ?></div>
                        <div class="user-email"><?php echo $learner['email']; ?></div>
                    </div>
                </div>
                <div class="user-details">
                    <div><strong>Learner ID:</strong> <?php echo $learner['learner_id']; ?></div>
                    <div><strong>Grade Level:</strong> <?php echo $learner['grade_level']; ?></div>
                    <div><strong>Created:</strong> <?php echo date('M d, Y', strtotime($learner['created_at'])); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Your existing overlay and register container remain exactly the same -->
    <div class="overlay" id="overlay"></div>

    <div class="register-container" id="registerContainer" style="display: none;">
        <form method="POST" action="api/addUsers.php" enctype="multipart/form-data" class="register-form">
            <div class="register-content">
                <div class="register-fields">
                    <div class="name-fields">
                        <div class="first-name">
                            <label for="firstName">First Name:</label>
                            <input type="text" name="firstName" id="first_name" required>
                        </div>
                        <div class="middle-initial">
                            <label for="middleInitial">Middle Initial:</label>
                            <input type="text" name="middleInitial" id="middle_initial" maxlength="5" required>
                        </div>
                    </div>

                    <div class="last-name">
                        <label for="lastName">Last Name:</label>
                        <input type="text" name="lastName" id="last_name" required>
                    </div>

                    <div class="email">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="password">
                        <label for="password">Password:</label>
                        <div class="input-wrapper" style="position: relative;">
                            <input type="password" name="password" id="password" required placeholder="Enter your password">
                            <i class="fas fa-eye-slash" id="togglePassword" style="font-size: .7rem; opacity: .7; color: #033153; position: absolute; right: 10px; top: 60%; transform: translateY(-50%); cursor: pointer;" onclick="togglePasswordVisibility('password')"></i>
                        </div>
                    </div>

                    <div class="confirm-password">
                        <label for="confirmPassword">Confirm Password:</label>
                        <div class="input-wrapper" style="position: relative;">
                            <input type="password" name="confirmPassword" id="confirmPassword" required placeholder="Confirm your password">
                            <i class="fas fa-eye-slash" id="toggleConfirmPassword" style="font-size: .7rem; opacity: .7; color: #033153; position: absolute; right: 10px; top: 60%; transform: translateY(-50%); cursor: pointer;" onclick="togglePasswordVisibility('confirmPassword')"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cancelRegister">
                <button type="button" class="cancelReg-btn" id="cancelBtn">CANCEL</button>
                <button type="submit" class="register-btn">REGISTER</button>  
            </div>
        </form>
    </div>

    <!-- Your existing JavaScript remains exactly the same -->
    <script src="interact/addUser.js"></script>
    <script>
        // Function to toggle password visibility
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
        
        // Add User modal toggle
        document.getElementById('addUserBtn').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('registerContainer').style.display = 'flex';
        });
        
        document.getElementById('cancelBtn').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('registerContainer').style.display = 'none';
        });
        
        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('registerContainer').style.display = 'none';
        });
    </script>
    <div class="password-field">
  <label>Password</label>
  <input type="password" id="password" value="alvarez123" readonly>
  <span class="toggle-password" onclick="togglePassword()">
    👁️ <!-- or use an icon font like Font Awesome -->
  </span>
</div>
</body>
</html>