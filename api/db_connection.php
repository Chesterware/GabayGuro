<?php

$host = "localhost";
$username = "root"; 
$password = "";
$database = "iskol4rx";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, 
        'cookie_secure' => false,
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}
?>