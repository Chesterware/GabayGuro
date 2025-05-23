<?php
require_once '../AdminPHP/admin_name.php';
require_once '../AdminPHP/dashboard_chart.php';
require_once '../AdminPHP/auth_admin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link rel="stylesheet" href="../AdminCSS/index.css" />
    <link rel="stylesheet" href="../AdminCSS/admin_dashboard.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
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

    <div class="status-users-container">
        <div class="row titles-row">
            <div class="col title">BOOKING STATUS COUNT</div>
            <div class="col title">USERS COUNT</div>
        </div>

        <div class="row counts-row">
            <div class="col">PENDING<br><strong><?= $statusCounts['PENDING'] ?></strong></div>
            <div class="col">ONGOING<br><strong><?= $statusCounts['ONGOING'] ?></strong></div>
            <div class="col">FOR REVIEW<br><strong><?= $statusCounts['FOR REVIEW'] ?></strong></div>
            <div class="col">COMPLETED<br><strong><?= $statusCounts['COMPLETED'] ?></strong></div>
            <div class="col">DECLINED<br><strong><?= $statusCounts['DECLINED'] ?></strong></div>
            <div class="col">CANCELLED<br><strong><?= $statusCounts['CANCELLED'] ?></strong></div>
            <div class="col">LEARNERS<br><strong><?= $learnerCount ?></strong></div>
            <div class="col">TUTORS<br><strong><?= $tutorCount ?></strong></div>
        </div>

    </div>

    <div class="chart-box">
        <div class="chart-container">
            <div class="chart-header">
                <h3>Subject Status Count</h3>
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

        <div class="chart-container">
            <div class="chart-header">
                <h3>Learners - Tutors Distribution</h3>
            </div>
            <canvas id="userPieChart"></canvas>
        </div>
    </div>

<script>
function toggleSidebar() {
    document.body.classList.toggle("sidebar-collapsed");

    const pieCanvas = document.getElementById('userPieChart');
    const pieContainer = pieCanvas.parentElement;

    pieCanvas.style.width = pieContainer.clientWidth + 'px';
    pieCanvas.style.height = pieContainer.clientHeight + 'px';

    subjectChart.resize();
    userPieChart.resize();
    userPieChart.update();
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
<script>
const pieCtx = document.getElementById('userPieChart').getContext('2d');
const userPieChart = new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Learners', 'Tutors'],
        datasets: [{
            data: [<?= $learnerCount ?>, <?= $tutorCount ?>],
            backgroundColor: ['#3498db', '#003153'],
            borderColor: ['#2980b9', '#27ae60'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
        padding: {
            top: 10,
            bottom: 100,
        }
    },  
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#444',
                    font: {
                        size: 20
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' users';
                    }
                }
            }
        }
    }
});
</script>

<script src="../../time-date-sidebar.js"></script>
</body>
</html>