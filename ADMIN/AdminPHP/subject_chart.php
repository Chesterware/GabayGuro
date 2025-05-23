<?php
require_once '../../db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../index.php");
    exit();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'day';

switch ($filter) {
    case 'week':
        $interval = "7 DAY";
        break;
    case 'month':
        $interval = "1 MONTH";
        break;
    case 'year':
        $interval = "1 YEAR";
        break;
    default:
        $interval = "1 DAY";
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
        AND b.date >= CURDATE() - INTERVAL $interval
    GROUP BY s.specialization_name
";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

$subjectData = [];
$totalBookingsBySubject = [];

while ($row = $result->fetch_assoc()) {
    $subject = $row['subject'];
    $count = (int)$row['count'];
    $subjectData[] = ['subject' => $subject, 'count' => $count];
    $totalBookingsBySubject[$subject] = $count;
}

arsort($totalBookingsBySubject);

$mostRequestedSubjects = [];
$mostRequestedCount = null;
foreach ($totalBookingsBySubject as $subject => $count) {
    if ($mostRequestedCount === null) {
        $mostRequestedCount = $count;
        $mostRequestedSubjects[] = $subject;
    } elseif ($count === $mostRequestedCount) {
        $mostRequestedSubjects[] = $subject;
    } else {
        break;
    }
}

if ($mostRequestedCount === 0) {
    $mostRequestedSubjects = [];
    $leastRequestedSubjects = [];
} else {
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
}

$zeroRequestedSubjects = [];
foreach ($totalBookingsBySubject as $subject => $count) {
    if ($count === 0) {
        $zeroRequestedSubjects[] = $subject;
    }
}
?>