<?php
require_once '../db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

date_default_timezone_set('Asia/Manila');

function redirectWithError($msg) {
    header("Location: ../confirm_email.php?error=" . urlencode($msg));
    exit;
}

function redirectWithSuccess($msg) {
    header("Location:  ../confirm_email.php?success=" . urlencode($msg));
    exit;
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isEmailExists($conn, $email) {
    $sql = "SELECT 1 FROM learner WHERE email = ? UNION SELECT 1 FROM tutor WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function sendConfirmationEmail($email, $role, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chesdeguzman05@gmail.com';
        $mail->Password   = 'cpjq xbpd hvyf gzyk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('chesdeguzman05@gmail.com', 'GabayGuro');
        $mail->addAddress($email);
        $mail->isHTML(true);

        $targetPage = $role === 'tutor' ? 'register_tutor.php' : 'register_learner.php';
        $link = "http://localhost/iskol4rx/$targetPage?token=$token";

        $mail->Subject = 'Confirm Your Registration';
        $mail->Body = "
            <p>Click the button below to complete your registration:</p>
            <p><a href='$link' style='padding:10px 20px; background:#003153; color:white; text-decoration:none; border-radius:5px;'>Verify Email</a></p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail error: {$mail->ErrorInfo}");
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';

    if (!isValidEmail($email)) {
        redirectWithError("Invalid email format.");
    }

    if (isEmailExists($conn, $email)) {
        redirectWithError("Email already exists.");
    }

    $token = generateToken();
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    if ($role === 'tutor') {
        $stmt = $conn->prepare("INSERT INTO tutor (email, verification_token, verification_expires, status) VALUES (?, ?, ?, 'For Verification')");
    } elseif ($role === 'learner') {
        $stmt = $conn->prepare("INSERT INTO learner (email, verification_token, verification_expires) VALUES (?, ?, ?)");
    } else {
        redirectWithError("Invalid role selected.");
    }

    $stmt->bind_param("sss", $email, $token, $expires);

    if (!$stmt->execute()) {
        redirectWithError("Database error: " . $stmt->error);
    }
    $stmt->close();

    if (!sendConfirmationEmail($email, $role, $token)) {
        redirectWithError("Failed to send verification email.");
    }

    redirectWithSuccess("An email confirmation has been sent to your account. Kindly check your inbox. Thank you!");
}
?>