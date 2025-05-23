<?php
require_once '../../db_connection.php';
require_once 'auth_admin.php';

$adminId = $_SESSION['admin_id'];

$sql = "SELECT first_name, middle_initial, last_name FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$adminFullName = $admin['first_name'];
if (!empty($admin['middle_initial'])) {
    $adminFullName .= ' ' . strtoupper($admin['middle_initial']);
}
$adminFullName .= ' ' . $admin['last_name'];
?>