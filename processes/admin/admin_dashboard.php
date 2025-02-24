<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "425096", "users");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('UTC');
$monthlyTransactionQuery = $conn->query("SELECT DATE_FORMAT(transaction_date, '%b') AS month, SUM(amount) AS total FROM transactions GROUP BY DATE_FORMAT(transaction_date, '%Y-%m') ORDER BY MIN(transaction_date)");
$months = [];
$totals = [];
while ($row = $monthlyTransactionQuery->fetch_assoc()) {
    $months[] = $row['month'];
    $totals[] = $row['total'];
}
$monthsJson = json_encode($months);
$totalsJson = json_encode($totals);

$totalProperties = $conn->query("SELECT COUNT(*) AS total FROM properties")->fetch_assoc()['total'];
$totalTransactions = $conn->query("SELECT COUNT(*) AS total FROM transactions")->fetch_assoc()['total'];
$totalTransactionAmount = $conn->query("SELECT SUM(amount) AS total FROM transactions")->fetch_assoc()['total'];

$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM info")->fetch_assoc()['total'];
$activeUsers = $conn->query("SELECT COUNT(*) AS total FROM info WHERE role='active'")->fetch_assoc()['total'];
$newUsers = $conn->query("SELECT COUNT(*) AS total FROM info WHERE registration_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['total'];

$recentTransactions = $conn->query("SELECT DATE_FORMAT(transaction_date, '%b') AS month, SUM(amount) AS total FROM transactions GROUP BY DATE_FORMAT(transaction_date, '%Y-%m') ORDER BY MIN(transaction_date) LIMIT 5");
$transactionMonths = [];
$transactionTotals = [];
while ($row = $recentTransactions->fetch_assoc()) {
    $transactionMonths[] = $row['month'];
    $transactionTotals[] = $row['total'];
}
$transactionMonthsJson = json_encode($transactionMonths);
$transactionTotalsJson = json_encode($transactionTotals);

$topProperties = $conn->query("SELECT properties.property_name, COUNT(transactions.transaction_id) AS transactions FROM properties JOIN transactions ON properties.property_id = transactions.property_id GROUP BY properties.property_id ORDER BY transactions DESC LIMIT 5");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .right-panel {
            margin-left: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .analytics, .recent-transactions, .top-properties, .user-stats {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'layout&others/sidebar.php'; ?>
    <div class="main-content d-flex">
        <div class="left-panel w-75">
            <div class="header d-flex justify-content-between align-items-center">
                <h2>Overview</h2>
            </div>
            <div class="cards row mt-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white p-3 text-center">
                        <h4>Total Sales</h4>
                        <h2>$<?php echo number_format($totalTransactionAmount, 2); ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white p-3 text-center">
                        <h4>Total Profit</h4>
                        <h2>$762.10</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white p-3 text-center">
                        <h4>Total Orders</h4>
                        <h2><?php echo $totalTransactions; ?></h2>
                    </div>
                </div>
            </div>
            <div class="analytics mt-4">
                <h3>Transactions Chart Flow</h3>
                <canvas id="transactionsChart"></canvas>
            </div>
        </div>
        <div class="right-panel w-25">
            <div class="user-stats">
                <h4>User Statistics</h4>
                <canvas id="userStatsChart"></canvas>
            </div>
            <div class="top-properties">
                <h4>Top Properties</h4>
                <ul>
                    <?php while ($row = $topProperties->fetch_assoc()) {
                        echo "<li>{$row['property_name']} - {$row['transactions']} sales</li>";
                    } ?>
                </ul>
            </div>
            <div class="recent-transactions">
                <h4>Recent Transactions</h4>
                <canvas id="recentTransactionsChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('transactionsChart'), {
            type: 'line',
            data: {
                labels: <?php echo $monthsJson; ?>,
                datasets: [{
                    label: 'Transactions',
                    data: <?php echo $totalsJson; ?>,
                    borderColor: 'green',
                    borderWidth: 2,
                    fill: false,
                    pointBackgroundColor: 'blue'
                }]
            }
        });

        new Chart(document.getElementById('userStatsChart'), {
            type: 'pie',
            data: {
                labels: ['Active Users', 'New Signups', 'Total Users'],
                datasets: [{
                    data: [<?php echo $activeUsers; ?>, <?php echo $newUsers; ?>, <?php echo $totalUsers; ?>],
                    backgroundColor: ['#36A2EB', '#FFCE56', '#FF6384']
                }]
            }
        });

        new Chart(document.getElementById('recentTransactionsChart'), {
            type: 'bar',
            data: {
                labels: <?php echo $transactionMonthsJson; ?>,
                datasets: [{
                    label: 'Recent Transactions',
                    data: <?php echo $transactionTotalsJson; ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                }]
            }
        });
    </script>
</body>
</html>
