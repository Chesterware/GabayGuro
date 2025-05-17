<?php
include('auth.php');

if ($_SESSION['role'] !== 'admin') {
    header("Location: eindex.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "task_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user_id, firstName, middleInitial, lastName, role FROM users";
$result = $conn->query($sql);

$admins = [];
$employees = [];

while ($row = $result->fetch_assoc()) {
    if ($row['role'] == 'admin') {
        $admins[] = $row;
    } else {
        $employees[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        $delete_sql = "DELETE FROM users WHERE user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param('i', $user_id);
        $delete_stmt->execute();
        $delete_stmt->close();
        header("Location: manageEmployee.php");
        exit();
    }

    if (isset($_POST['edit_user'])) {
        $user_id = $_POST['user_id'];
        $firstName = $_POST['firstName'];
        $middleInitial = $_POST['middleInitial'];
        $lastName = $_POST['lastName'];

        $edit_sql = "UPDATE users SET firstName = ?, middleInitial = ?, lastName = ? WHERE user_id = ?";
        $edit_stmt = $conn->prepare($edit_sql);
        $edit_stmt->bind_param('sssi', $firstName, $middleInitial, $lastName, $user_id);
        $edit_stmt->execute();
        $edit_stmt->close();
        header("Location: manageEmployee.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="../AdminCSS/manageEmployee.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<!-- Sidebar -->
<nav class="side-bar" id="sidebar">
    <div class="logo-details">
        <img src="images/TaskFlow.png" alt="Taskify Logo" class="logo-img">
        <h3 class="logo-name">Task Flow</h3>
    </div>
    <div class="nav-bar">
        <a href="aindex.php"><i class="fa fa-tasks"></i><span> Home</span></a>
        <a href="taskStatus.php"><i class="fa fa-check"></i><span> Task Status</span></a>
        <a href="manageEmployee.php"><i class="fa fa-users"></i><span> Employee</span></a>
        <a href="#" id="account-settings-link"><i class="fa fa-cogs" aria-hidden="true"></i><span>Edit Profile</span></a>
        <a href="aboutUs.php"><i class="fa fa-info-circle"></i><span> About Us</span></a>
        <a href="logout.php"><i class="fa fa-sign-out"></i><span> Sign Out</span></a>
    </div>
    <div class="profile-details">
        <p><?php echo htmlspecialchars($_SESSION['first_name']); ?></p>
        <p>Admin ID: <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
    </div>
</nav>
<!-- End of Sidebar -->
<section class="home-section" id="main">
    <div class="main-content">
        <div class="home-content">
            <button id="toggleSidebar" class="sidebar-toggle-button">&#9776;</button>
            <div class="welcome-name-container">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?></h2>
            </div>
        </div>
        <div class="user-section">
            <!-- Admin Users -->
            <div class="admin-section">
                <h3>Admins</h3>
                <?php if (count($admins) > 0): ?>
                    <?php foreach ($admins as $admin): ?>
                        <div class="user-box">
                            <p><?php echo htmlspecialchars($admin['lastName']) . ", " . htmlspecialchars($admin['firstName']) . " " . htmlspecialchars($admin['middleInitial']); ?></p>
                            <p><span>Admin ID:</span> <?php echo htmlspecialchars($admin['user_id']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No admins found.</p>
                <?php endif; ?>
            </div>

            <!-- Employee Users -->
            <div class="employee-section">
                <h3>Employees</h3>
                <?php if (count($employees) > 0): ?>
                    <?php foreach ($employees as $employee): ?>
                        <div class="user-box">
                            <p><?php echo htmlspecialchars($employee['lastName']) . ", " . htmlspecialchars($employee['firstName']) . " " . htmlspecialchars($employee['middleInitial']); ?></p>
                            <p><span>User ID:</span> <?php echo htmlspecialchars($employee['user_id']); ?></p>

                            <!-- Edit and Delete -->
                            <form action="manageEmployee.php" method="post" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($employee['user_id']); ?>">
                                <button type="button" class="edit-btn" 
                                        data-user-id="<?php echo $employee['user_id']; ?>"
                                        data-first-name="<?php echo $employee['firstName']; ?>"
                                        data-middle-initial="<?php echo $employee['middleInitial']; ?>"
                                        data-last-name="<?php echo $employee['lastName']; ?>"
                                        onclick="openEditForm(this)">Edit</button>
                                <button type="submit" name="delete_user" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No employees found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Form for Employee -->
    <div id="editOverlay" class="overlay">
        <div class="modal">
            <span class="close-btn" onclick="closeEditForm()">&#10006;</span>
            <h3>Edit Employee Info</h3>
            <form action="manageEmployee.php" method="post">
                <input type="hidden" name="user_id" id="editUserId">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" id="editFirstName" required>
                <label for="middleInitial">Middle Initial</label>
                <input type="text" name="middleInitial" id="editMiddleInitial" required>
                <label for="lastName">Last Name</label>
                <input type="text" name="lastName" id="editLastName" required>
                <button type="submit" name="edit_user">Save Changes</button>
            </form>
        </div>
    </div>
</section>

<div id="profile-overlay" class="profile-overlay">
    <div class="profile-form-container">
        <button class="close-btn" onclick="closeForm()">&times;</button>
        <h2>Edit Profile</h2>
        <form id="profile-form" method="POST" action="updateProfile.php">
            <label for="first-name">First Name:</label>
            <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($_SESSION['first_name']); ?>" required>

            <label for="middle-name">Middle Initial:</label>
            <input type="text" id="middle_initial" name="middle_initial" placeholder="Middle Initial">

            <label for="last-name">Last Name:</label>
            <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($_SESSION['last_name']); ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm_password" required>

            <div class="form-buttons">
                <button type="reset" id="reset-button">Reset</button>
                <button type="submit" id="save-button" name="updateProfile">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditForm(button) {
        // Retrieve employee data from button's data attributes
        const userId = button.getAttribute('data-user-id');
        const firstName = button.getAttribute('data-first-name');
        const middleInitial = button.getAttribute('data-middle-initial');
        const lastName = button.getAttribute('data-last-name');

        // Populate modal fields with data
        document.getElementById('editUserId').value = userId;
        document.getElementById('editFirstName').value = firstName;
        document.getElementById('editMiddleInitial').value = middleInitial;
        document.getElementById('editLastName').value = lastName;

        // Show the modal
        document.getElementById('editOverlay').style.display = 'flex';
    }

    function closeEditForm() {
        document.getElementById('editOverlay').style.display = 'none';
    }

    document.getElementById('account-settings-link').addEventListener('click', function() {
        document.getElementById('profile-overlay').style.display = 'flex';
    });
    
    function closeForm() {
        const overlays = document.querySelectorAll('.overlay, .profile-overlay');
        overlays.forEach(overlay => {
            overlay.style.display = 'none';
            const form = overlay.querySelector('form');
            if (form) form.reset();
        });
    }
</script>

</body>
</html>
