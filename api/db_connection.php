<?php

$host = "localhost";
$username = "root"; 
$password = "appdev_Iskol4rx";
$database = "iskol4rx";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for proper encoding
$conn->set_charset("utf8mb4");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, // 1 day
        'cookie_secure' => false, // Set to true if using HTTPS
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}
?>