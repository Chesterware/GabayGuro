<?php
require_once '../../db_connection.php';
require_once 'auth_admin.php';

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

$sql = "
    SELECT status, COUNT(*) AS count
    FROM bookings
    WHERE is_deleted = 0 
      AND date >= CURDATE() - INTERVAL $interval
    GROUP BY status
";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

$statusCounts = [
    'PENDING' => 0,
    'ONGOING' => 0,
    'FOR REVIEW' => 0,
    'COMPLETED' => 0,
    'DECLINED' => 0,
    'CANCELLED' => 0,
];

while ($row = $result->fetch_assoc()) {
    $status = strtoupper($row['status']);
    if (array_key_exists($status, $statusCounts)) {
        $statusCounts[$status] = (int)$row['count'];
    }
}

$subjectData = [];
foreach ($statusCounts as $status => $count) {
    $subjectData[] = ['subject' => $status, 'count' => $count];
}

$learnerCountQuery = $conn->query("SELECT COUNT(*) AS total FROM learner");
$learnerCount = $learnerCountQuery ? (int)$learnerCountQuery->fetch_assoc()['total'] : 0;

$tutorCountQuery = $conn->query("SELECT COUNT(*) AS total FROM tutor");
$tutorCount = $tutorCountQuery ? (int)$tutorCountQuery->fetch_assoc()['total'] : 0;
?>