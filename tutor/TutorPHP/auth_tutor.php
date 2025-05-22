<?php
if (!isset($_SESSION['tutor_id'])) {
    header("Location: ../../index.php");
    exit();
}
?>