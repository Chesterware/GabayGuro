<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tutor_id = $_POST['tutor_id'] ?? null;
    $new_status = $_POST['new_status'] ?? null;

    if ($tutor_id && in_array($new_status, ['verified', 'unverified'])) {
        $stmt = $conn->prepare("UPDATE tutor SET status = ? WHERE tutor_id = ?");
        $stmt->bind_param("si", $new_status, $tutor_id);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
        $stmt->close();
    } else {
        echo "invalid";
    }
} else {
    echo "forbidden";
}
?>
