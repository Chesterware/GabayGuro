<?php
require_once(__DIR__ . '/../db_connection.php');
require_once 'learner_tokens.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password === '') {
        $errors[] = 'Password is required.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    $first_name = trim($_POST['first_name'] ?? '');
    $middle_initial = trim($_POST['middle_initial'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $school_affiliation = trim($_POST['school_affiliation'] ?? '');
    $grade_level = $_POST['grade_level'] ?? 'N/A';
    $strand = $_POST['strand'] ?? 'N/A';

    if ($first_name === '') $errors[] = 'First Name is required.';
    if ($last_name === '') $errors[] = 'Last Name is required.';
    if ($birthdate === '') $errors[] = 'Birthdate is required.';
    if (!in_array($grade_level, ['N/A','G7','G8','G9','G10','G11','G12'])) {
        $errors[] = 'Invalid Grade Level.';
    }
    if (!in_array($strand, ['N/A','STEM','ABM','HUMSS','GAS'])) {
        $errors[] = 'Invalid Strand.';
    }

    // Handle profile picture upload as blob
    $profile_picture_blob = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            $errors[] = 'Profile picture must be JPG, PNG, or GIF.';
        } elseif ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Error uploading profile picture.';
        } else {
            $profile_picture_blob = file_get_contents($_FILES['profile_picture']['tmp_name']);
        }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL with profile_picture as blob (can be NULL)
        $sql = "UPDATE learner SET password = ?, first_name = ?, middle_initial = ?, last_name = ?, birthdate = ?, profile_picture = ?, school_affiliation = ?, grade_level = ?, strand = ? WHERE learner_id = ?";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $errors[] = "Prepare failed: " . $conn->error;
        } else {
            // For blob, bind param as "b" for blob, else "s" for string
            // Types: s=string, b=blob, i=integer
            $null = null; // for sending null blob if no picture

            $profile_picture_param = $profile_picture_blob ?? $null;

            $stmt->bind_param(
                "ssssbssssi",
                $hashed_password,
                $first_name,
                $middle_initial,
                $last_name,
                $birthdate,
                $profile_picture_param,
                $school_affiliation,
                $grade_level,
                $strand,
                $learner['learner_id']
            );

            // Send blob data if present
            if ($profile_picture_blob !== null) {
                $stmt->send_long_data(5, $profile_picture_blob);
            }

            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Database update failed: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>