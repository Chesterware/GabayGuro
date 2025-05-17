<?php
require_once 'db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$adminId = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT first_name, middle_initial, last_name, email, password FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $stmt->bind_result($first_name, $middle_initial, $last_name, $email, $password, );
    $stmt->fetch();
    $stmt->close();
    
    $masked_password = "********";
    
    $_SESSION['first_name'] = $first_name;
    $_SESSION['middle_initial'] = $middle_initial;
    $_SESSION['last_name'] = $last_name;
    $_SESSION['email'] = $email;

}
?>