<?php
// Start the session and check if the admin is logged in
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['email'])) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "425096", "users");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Monthly Transaction Data
$monthlyTransactionQuery = $conn->query("SELECT DATE_FORMAT(transaction_date, '%b') AS month, SUM(amount) AS total FROM transactions GROUP BY DATE_FORMAT(transaction_date, '%Y-%m') ORDER BY MIN(transaction_date)");

// Prepare data for Chart.js
$months = [];
$totals = [];
while ($row = $monthlyTransactionQuery->fetch_assoc()) {
    $months[] = $row['month'];
    $totals[] = $row['total'];
}

// Convert to JSON for JavaScript
$monthsJson = json_encode($months);
$totalsJson = json_encode($totals);

// Fetch total counts and amounts
$totalProperties = $conn->query("SELECT COUNT(*) AS total FROM properties")->fetch_assoc()['total'];
$totalTransactions = $conn->query("SELECT COUNT(*) AS total FROM transactions")->fetch_assoc()['total'];
$totalTransactionAmount = $conn->query("SELECT SUM(amount) AS total FROM transactions")->fetch_assoc()['total'];

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
        body {
            background-color: #f4f4f4;
            transition: background 0.3s, color 0.3s;
        }
        .dark-mode {
            background-color: #212529;
            color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            padding: 10px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .card {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .toggle-container {
            text-align: right;
            padding: 10px;
        }
        .toggle-button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include('layout&others/sidebar.php'); ?>

    <div class="content">
        <div class="toggle-container">
            <button class="toggle-button" onclick="toggleDarkMode()">🌙</button>
        </div>
        <h2>Admin Dashboard</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h4>Total Properties</h4>
                    <h2><?php echo $totalProperties; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h4>Total Transactions</h4>
                    <h2><?php echo $totalTransactions; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h4>Total Amount Transacted</h4>
                    <h2>Ksh<?php echo number_format($totalTransactionAmount, 2); ?></h2>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3>Transaction Flow</h3>
            <canvas id="transactionChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $monthsJson; ?>,
                datasets: [{
                    label: 'Transaction Flow',
                    data: <?php echo $totalsJson; ?>,
                    borderColor: 'black',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
            const button = document.querySelector(".toggle-button");
            if (document.body.classList.contains("dark-mode")) {
                button.textContent = "☀️";
            } else {
                button.textContent = "🌙";
            }
        }
    </script>
</body>
</html>
