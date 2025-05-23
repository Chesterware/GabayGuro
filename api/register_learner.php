<?php
require_once(__DIR__ . '/../db_connection.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['register_email']) || empty($_SESSION['register_password']) || ($_SESSION['register_role'] ?? '') !== 'learner') {
    header("Location: register.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = htmlspecialchars($_POST['first_name']);
    $middle_initial = htmlspecialchars($_POST['middle_initial'] ?? '');
    $last_name = htmlspecialchars($_POST['last_name']);
    $birthdate = $_POST['birthdate'];
    $school_affiliation = htmlspecialchars($_POST['school_affiliation']);
    $grade_level = $_POST['grade_level'];
    $strand = $_POST['strand'];

    $profile_picture = '';
    if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $file_type = mime_content_type($_FILES['profile_picture']['tmp_name']);
        
        if (in_array($file_type, $allowed_types)) {
            $profile_name = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
            $profile_picture = 'uploads/profile_pictures/' . $profile_name;
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
        } else {
            $error = "Profile picture must be JPG or PNG file";
        }
    }

    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO learner (email, password, first_name, middle_initial, last_name, birthdate, profile_picture, school_affiliation, grade_level, strand) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $_SESSION['register_email'], $_SESSION['register_password'], $first_name, $middle_initial, $last_name, $birthdate, $profile_picture, $school_affiliation, $grade_level, $strand);
        
        if ($stmt->execute()) {
            unset($_SESSION['register_email']);
            unset($_SESSION['register_password']);
            unset($_SESSION['register_role']);
            
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>