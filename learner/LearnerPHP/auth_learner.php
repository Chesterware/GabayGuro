<?php
if (!isset($_SESSION['learner_id'])) {
    header("Location: ../../index.php");
    exit();
}
?>
