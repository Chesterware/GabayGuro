<?php
require_once '../AdminPHP/db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../login.php");
    exit();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'day';

switch ($filter) {
    case 'week':
        $interval = "INTERVAL 7 DAY";
        break;
    case 'month':
        $interval = "INTERVAL 1 MONTH";
        break;
    case 'year':
        $interval = "INTERVAL 1 YEAR";
        break;
    default:
        $interval = "INTERVAL 1 DAY";
        break;
}

$subjectQuery = "SELECT specialization_name FROM specializations";
$subjectResult = $conn->query($subjectQuery);

$allSubjects = [];
while ($row = $subjectResult->fetch_assoc()) {
    $allSubjects[] = $row['specialization_name'];
}

$sql = "
    SELECT s.specialization_name AS subject, COUNT(b.booking_id) AS count
    FROM specializations s
    LEFT JOIN bookings b 
        ON b.subject = s.specialization_name 
        AND b.is_deleted = 0 
        AND b.date >= CURDATE() - $interval
    GROUP BY s.specialization_name
";
$result = $conn->query($sql);

$subjectData = [];
$totalBookingsBySubject = [];
while ($row = $result->fetch_assoc()) {
    $subjectData[] = $row;
    $totalBookingsBySubject[$row['subject']] = (int)$row['count'];
}

arsort($totalBookingsBySubject);
$mostRequestedSubject = key($totalBookingsBySubject);
$mostRequestedCount = current($totalBookingsBySubject);

$leastRequestedSubject = null;
$leastRequestedCount = null;
foreach (array_reverse($totalBookingsBySubject, true) as $subject => $count) {
    if ($count > 0) {
        $leastRequestedSubject = $subject;
        $leastRequestedCount = $count;
        break;
    }
}

$zeroRequestedSubjects = [];
foreach ($totalBookingsBySubject as $subject => $count) {
    if ($count == 0) {
        $zeroRequestedSubjects[] = $subject;
    }
}

arsort($totalBookingsBySubject);
$mostRequestedSubject = key($totalBookingsBySubject);
$mostRequestedCount = current($totalBookingsBySubject);

$minCount = null;
foreach ($totalBookingsBySubject as $count) {
    if ($count > 0 && ($minCount === null || $count < $minCount)) {
        $minCount = $count;
    }
}

$leastRequestedSubjects = [];
if ($minCount !== null) {
    foreach ($totalBookingsBySubject as $subject => $count) {
        if ($count === $minCount) {
            $leastRequestedSubjects[] = $subject;
        }
    }
}

$zeroRequestedSubjects = [];
foreach ($totalBookingsBySubject as $subject => $count) {
    if ($count == 0) {
        $zeroRequestedSubjects[] = $subject;
    }
}

?>