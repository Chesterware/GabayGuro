<?php
require_once __DIR__ . '/../../api/db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$status = isset($_GET['status']) ? $_GET['status'] : 'Pending'; 

$query = "SELECT tutor.tutor_id, tutor.first_name, tutor.middle_initial, tutor.last_name, ea.attainment_name AS educational_attainment, tutor.diploma, tutor.other_certificates, tutor.status
          FROM tutor
          LEFT JOIN educationalattainments ea ON tutor.educational_attainment = ea.attainment_id
          WHERE tutor.status = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $status);
$stmt->execute();
$result = $stmt->get_result();
$tutors = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['verify_tutor_id'])) {
    $tutor_id = $_POST['verify_tutor_id'];
    $updateQuery = "UPDATE tutor SET status = 'Verified' WHERE tutor_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('i', $tutor_id);  // 'i' stands for integer
    $updateStmt->execute();
}

if (isset($_POST['unverify_tutor_id'])) {
    $tutor_id = $_POST['unverify_tutor_id'];
    $updateQuery = "UPDATE tutor SET status = 'Not Verified' WHERE tutor_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('i', $tutor_id);
    $updateStmt->execute();
}

?>