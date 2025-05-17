<?php
require_once 'db_connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['register_email']) || empty($_SESSION['register_password']) || (isset($_SESSION['register_role']) && $_SESSION['register_role'] !== 'tutor')) {
    header("Location: register.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
    $middle_initial = isset($_POST['middle_initial']) ? htmlspecialchars($_POST['middle_initial']) : '';
    $last_name = isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '';
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
    
    $specializations = isset($_POST['specializations']) ? $_POST['specializations'] : array();
    
    $educational_attainment = isset($_POST['educational_attainment']) ? $_POST['educational_attainment'] : '';
    $years_of_experience = isset($_POST['years_of_experience']) ? $_POST['years_of_experience'] : '';
    $rate_per_hour = isset($_POST['rate_per_hour']) ? (float)$_POST['rate_per_hour'] : 0;
    $rate_per_session = isset($_POST['rate_per_session']) ? (float)$_POST['rate_per_session'] : 0;

    $diploma_path = '';
    $certificates_path = '';
    
    if (isset($_FILES['diploma']) && $_FILES['diploma']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
        $file_type = mime_content_type($_FILES['diploma']['tmp_name']);
        
        if (in_array($file_type, $allowed_types)) {
            $diploma_name = uniqid() . '_' . basename($_FILES['diploma']['name']);
            $diploma_path = 'uploads/diplomas/' . $diploma_name;
            move_uploaded_file($_FILES['diploma']['tmp_name'], $diploma_path);
        } else {
            $error = "Diploma must be PDF, JPG, or PNG file";
        }
    }
    
    if (isset($_FILES['certificates']) && $_FILES['certificates']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
        $file_type = mime_content_type($_FILES['certificates']['tmp_name']);
        
        if (in_array($file_type, $allowed_types)) {
            $certificates_name = uniqid() . '_' . basename($_FILES['certificates']['name']);
            $certificates_path = 'uploads/certificates/' . $certificates_name;
            move_uploaded_file($_FILES['certificates']['tmp_name'], $certificates_path);
        } else {
            $error = "Certificates must be PDF, JPG, or PNG file";
        }
    }

    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO tutor (email, password, first_name, middle_initial, last_name, birthdate, educational_attainment, years_of_experience, diploma, other_certificates, rate_per_hour, rate_per_session, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssssssssssdd", $_SESSION['register_email'], $_SESSION['register_password'], $first_name, $middle_initial, $last_name, $birthdate, $educational_attainment, $years_of_experience, $diploma_path, $certificates_path, $rate_per_hour, $rate_per_session);
        
        if ($stmt->execute()) {
            $tutor_id = $conn->insert_id;

            foreach ($specializations as $spec_id) {
                $spec_id = (int)$spec_id;
                $spec_stmt = $conn->prepare("INSERT INTO tutor_specializations (tutor_id, specialization_id) VALUES (?, ?)");
                $spec_stmt->bind_param("ii", $tutor_id, $spec_id);
                $spec_stmt->execute();
            }

            unset($_SESSION['register_email']);
            unset($_SESSION['register_password']);
            unset($_SESSION['register_role']);
            
            $success = "Registration successful! Your application is under review.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>