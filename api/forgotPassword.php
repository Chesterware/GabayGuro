<?php
require_once 'api/db_connection.php';
require 'vendor/autoload.php'; // Require PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error_message = "Please enter your email address.";
    } else {
        // Check if email exists in admin table
        $stmt = $conn->prepare("SELECT admin_id, first_name, last_name FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            // Generate a unique token
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime("+24 hours")); 
            
            // Store token in database
            $stmt = $conn->prepare("UPDATE admin SET reset_token = ?, reset_token_expires = ? WHERE admin_id = ?");
            $stmt->bind_param("ssi", $token, $expires, $admin['admin_id']);
            $stmt->execute();
            
            // Send email with reset link
            $mail = new PHPMailer(true);
            
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = 'jonzeph2005@gmail.com'; // Replace with your email
                $mail->Password   = 'lnroewtptplkrfil'; // Replace with your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                
                // Recipients
                $mail->setFrom('your_email@gmail.com', 'Gabay Guro Admin');
                $mail->addAddress($email, $admin['first_name'] . ' ' . $admin['last_name']);
                
                // Content
                $reset_link = "http://localhost/iskol4rx/reset_password.php?token=$token"; // Replace with your domain
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = "Hello {$admin['first_name']},<br><br>
                                  You requested a password reset. Please click the link below to reset your password:<br><br>
                                  <a href='$reset_link'>$reset_link</a><br><br>
                                  This link will expire in 1 hour.<br><br>
                                  If you didn't request this, please ignore this email.";
                $mail->AltBody = "Hello {$admin['first_name']},\n\nYou requested a password reset. Please visit this link to reset your password:\n\n$reset_link\n\nThis link will expire in 1 hour.";
                
                $mail->send();
                $success_message = "A password reset link has been sent to your email address.";
            } catch (Exception $e) {
                $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $error_message = "If this email exists in our system, a reset link has been sent.";
            // We don't reveal if the email exists or not for security
        }
    }
}
?>