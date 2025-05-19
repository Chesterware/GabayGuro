<?php
require_once '../AdminPHP/admin_name.php';
require_once '../AdminPHP/subject_chart.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../AdminCSS/index.css" />
    <link rel="stylesheet" href="../AdminCSS/admin_dashboard.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="sidebar-collapsed">
    <div class="header-title">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="page-label">DASHBOARD</div>
        <div class="datetime" id="datetime"></div>
    </div>

    <div class="sidebar">
        <h2 class="admin-name"><?php echo htmlspecialchars($adminFullName); ?></h2>
        <h3 class="sidebar-label">TAGAPAG GABAY</h3>
        <div class="separator"></div>

        <form action="" method="GET">
            <button type="submit" class="btn active"><i class="fas fa-tachometer-alt"></i>Dashboard</button>
        </form>
        <form action="tutor_verification.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-check-circle"></i>Tutor Verification</button>
        </form>
        <form action="manage_users.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-users-cog"></i>Manage Users</button>
        </form>
        <form action="admin_profile.php" method="GET">
            <button type="submit" class="btn"><i class="fas fa-user-cog"></i>Admin Profile</button>
        </form>

        <div class="separator"></div>

        <form action="../../api/logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>

        <div class="chart-and-interpretation">
        <div class="chart-container">
            <div class="chart-header">
                <h3>Most Requested Subjects</h3>
                <form method="GET" class="filter-form">
                    <select name="filter" id="filter" onchange="this.form.submit()">
                        <option value="day" <?= $filter === 'day' ? 'selected' : '' ?>>DAY</option>
                        <option value="week" <?= $filter === 'week' ? 'selected' : '' ?>>WEEK</option>
                        <option value="month" <?= $filter === 'month' ? 'selected' : '' ?>>MONTH</option>
                        <option value="year" <?= $filter === 'year' ? 'selected' : '' ?>>YEAR</option>
                    </select>
                </form>
            </div>
            <canvas id="subjectChart"></canvas>
        </div>

        <div class="summary-grid">
<div class="summary-card">
                    <h3>Total Bookings</h3>
                    <p><?= array_sum($totalBookingsBySubject) ?> bookings in the selected <?= htmlspecialchars($filter) ?></p>
                </div>

                <div class="summary-card">
                    <h3>Most Requested Subject</h3>
                    <ul class="subject-list">
                        <li><?= htmlspecialchars($mostRequestedSubject) ?> (<?= $mostRequestedCount ?> bookings)</li>
                    </ul>
                </div>

                <div class="summary-card">
                    <h3>Least Requested Subject(s)</h3>
                    <?php if (!empty($leastRequestedSubjects)): ?>
                        <ul class="subject-list">
                            <?php foreach ($leastRequestedSubjects as $subject): ?>
                                <li><?= htmlspecialchars($subject) ?> (<?= $minCount ?> bookings)</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>N/A</p>
                    <?php endif; ?>
                </div>

                <div class="summary-card">
                    <h3>Subjects with Zero Bookings</h3>
                    <?php if (count($zeroRequestedSubjects) > 0): ?>
                        <ul class="subject-list">
                            <?php foreach ($zeroRequestedSubjects as $subject): ?>
                                <li><?= htmlspecialchars($subject) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>None</p>
                    <?php endif; ?>
                </div>
        </div>
    </div>
<script>
function toggleSidebar() {
    document.body.classList.toggle("sidebar-collapsed");
}

const ctx = document.getElementById('subjectChart').getContext('2d');
const subjectChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($subjectData, 'subject')) ?>,
        datasets: [{
            label: 'Number of Requests',
            data: <?= json_encode(array_column($subjectData, 'count')) ?>,
            backgroundColor: '#003153',
            borderColor: '#001F3F',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 20,
                right: 20,
                top: 5,
                bottom: 100  
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                min: 0,
                ticks: {
                    precision: 0,
                    color: '#003153',
                    font: {
                        size: 12
                    },
                    padding: 10 
                },
                grid: {
                    drawBorder: true,
                    drawTicks: true,
                }
            },
            x: {
                ticks: {
                    color: '#000',
                    font: {
                        size: 12
                    }
                },
                grid: {
                    drawBorder: false,
                    drawTicks: false,
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: '#444',
                    font: {
                        size: 14
                    }
                }
            }
        }
    }
});
</script>

<script src="../AdminJS/time_date.js"></script>
</body>
</html>