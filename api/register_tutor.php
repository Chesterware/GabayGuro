<?php
require_once(__DIR__ . '/../db_connection.php');
require_once 'tutor_tokens.php';

$errors = [];

$specializations = [];
$res = $conn->query("SELECT specialization_id, specialization_name, category FROM specializations ORDER BY category, specialization_name");
while ($row = $res->fetch_assoc()) {
    $specializations[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_initial = trim($_POST['middle_initial'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $educational_attainment = $_POST['educational_attainment'] ?? '';
    $years_of_experience = $_POST['years_of_experience'] ?? '';
    $rate_per_hour = $_POST['rate_per_hour'] ?? null;
    $rate_per_session = $_POST['rate_per_session'] ?? null;
    $selected_specializations = $_POST['specializations'] ?? [];

    if ($password === '') {
        $errors[] = "Password is required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number.";
    }

    if ($first_name === '' || $last_name === '' || $birthdate === '' || $educational_attainment === '' || $years_of_experience === '') {
        $errors[] = "Please fill in all required fields.";
    }

    if ($middle_initial !== '' && strlen($middle_initial) !== 1) {
        $errors[] = "Middle initial must be exactly one character.";
    }

    if ($rate_per_hour !== null && $rate_per_hour !== '' && (!is_numeric($rate_per_hour) || $rate_per_hour < 0)) {
        $errors[] = "Rate per hour must be a positive number.";
    }
    if ($rate_per_session !== null && $rate_per_session !== '' && (!is_numeric($rate_per_session) || $rate_per_session < 0)) {
        $errors[] = "Rate per session must be a positive number.";
    }

    if (!isset($_FILES['diploma']) || $_FILES['diploma']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Diploma file is required.";
    } else {
        $diplomaContent = file_get_contents($_FILES['diploma']['tmp_name']);
    }

    $otherCertificatesContent = null;
    if (isset($_FILES['other_certificates']) && isset($_FILES['other_certificates']['tmp_name'][0]) && $_FILES['other_certificates']['error'][0] !== UPLOAD_ERR_NO_FILE) {
        $otherCertificatesContent = file_get_contents($_FILES['other_certificates']['tmp_name'][0]);
    }

    $profilePictureContent = null;
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $profilePictureContent = file_get_contents($_FILES['profile_photo']['tmp_name']);
    } else {
        $errors[] = "Profile picture upload failed or was not provided.";
    }

    if (!is_array($selected_specializations)) {
        $errors[] = "Invalid specializations selected.";
    } else {
        $valid_spec_ids = array_column($specializations, 'specialization_id');
        foreach ($selected_specializations as $spec_id) {
            if (!in_array($spec_id, $valid_spec_ids, true)) {
                $errors[] = "Invalid specialization selected.";
                break;
            }
        }
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE tutor SET 
            password = ?, 
            first_name = ?, 
            middle_initial = ?, 
            last_name = ?, 
            birthdate = ?, 
            educational_attainment = ?, 
            years_of_experience = ?, 
            profile_picture = ?, 
            diploma = ?, 
            other_certificates = ?, 
            rate_per_hour = ?, 
            rate_per_session = ?, 
            verification_token = NULL, 
            verification_expires = NULL, 
            status = 'For Verification' 
            WHERE tutor_id = ?");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $rate_per_hour = ($rate_per_hour === '' || $rate_per_hour === null) ? null : floatval($rate_per_hour);
        $rate_per_session = ($rate_per_session === '' || $rate_per_session === null) ? null : floatval($rate_per_session);

        $null = null;

        $stmt->bind_param(
            "sssssssbbbddi",
            $password_hash,
            $first_name,
            $middle_initial,
            $last_name,
            $birthdate,
            $educational_attainment,
            $years_of_experience,
            $null,
            $null,
            $null,
            $rate_per_hour,
            $rate_per_session,
            $tutor['tutor_id']
        );

        $stmt->send_long_data(7, $profilePictureContent);
        $stmt->send_long_data(8, $diplomaContent);
        if ($otherCertificatesContent !== null) {
            $stmt->send_long_data(9, $otherCertificatesContent);
        }

        if ($stmt->execute()) {
            $delStmt = $conn->prepare("DELETE FROM tutor_specializations WHERE tutor_id = ?");
            $delStmt->bind_param("i", $tutor['tutor_id']);
            $delStmt->execute();
            $delStmt->close();

            $insStmt = $conn->prepare("INSERT INTO tutor_specializations (tutor_id, specialization_id) VALUES (?, ?)");
            foreach ($selected_specializations as $spec_id) {
                $insStmt->bind_param("ii", $tutor['tutor_id'], $spec_id);
                $insStmt->execute();
            }
            $insStmt->close();

            header("Location: tutor/TutorHTML/ratings_review.php");
            exit;
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>