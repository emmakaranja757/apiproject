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

$monthlyTransactionQuery = $conn->query("SELECT DATE_FORMAT(transaction_date, '%b') AS month, SUM(amount) AS total FROM transactions GROUP BY DATE_FORMAT(transaction_date, '%Y-%m') ORDER BY MIN(transaction_date)");
$months = [];
$totals = [];
while ($row = $monthlyTransactionQuery->fetch_assoc()) {
    $months[] = $row['month'];
    $totals[] = $row['total'];
}
$monthsJson = json_encode($months);
$totalsJson = json_encode($totals);

$totalPropertiesQuery = $conn->query("SELECT COUNT(*) AS total FROM properties");
$totalProperties = $totalPropertiesQuery->fetch_assoc()['total'];

$totalTransactionsQuery = $conn->query("SELECT COUNT(*) AS total FROM transactions");
$totalTransactions = $totalTransactionsQuery->fetch_assoc()['total'];

$totalTransactionAmountQuery = $conn->query("SELECT SUM(amount) AS total FROM transactions");
$totalTransactionAmount = $totalTransactionAmountQuery->fetch_assoc()['total'];

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
        }
        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 200px;
        }
        .header {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .cards .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .analytics {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .dark-mode {
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'layout&others/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header d-flex justify-content-between align-items-center">
            <h2>Overview</h2>
            <button class="btn btn-dark" id="darkModeToggle">🌙</button>
        </div>
        
        <div class="cards row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h4>Total Sales</h4>
                        <h2>$<?php echo number_format($totalTransactionAmount, 2); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h4>Total Profit</h4>
                        <h2>$762.10</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body text-center">
                        <h4>Total Orders</h4>
                        <h2><?php echo $totalTransactions; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="analytics mt-4">
            <h3>Transactions Chart Flow</h3>
            <canvas id="transactionsChart"></canvas>
        </div>
    </div>

    <script>
        if (localStorage.getItem("dark-mode") === "enabled") {
            document.body.classList.add("dark-mode");
        }
        document.getElementById("darkModeToggle").addEventListener("click", function() {
            document.body.classList.toggle("dark-mode");
            localStorage.setItem("dark-mode", document.body.classList.contains("dark-mode") ? "enabled" : "disabled");
        });
        
        const ctx = document.getElementById('transactionsChart').getContext('2d');
        new Chart(ctx, {
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
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'black',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
