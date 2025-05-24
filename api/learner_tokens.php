<?php
require_once(__DIR__ . '/../db_connection.php');

date_default_timezone_set('Asia/Manila');

$token = $_GET['token'] ?? '';
if (!$token) {
    die("Token not provided.");
}

$stmt = $conn->prepare("SELECT learner_id, email FROM learner WHERE verification_token = ? AND verification_expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$learner = $result->fetch_assoc();
$stmt->close();

if (!$learner) {
    die("Invalid or expired token.");
}
?>