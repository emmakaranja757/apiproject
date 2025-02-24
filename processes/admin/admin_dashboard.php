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
$monthlyTransactionQuery = $conn->query("
    SELECT DATE_FORMAT(transaction_date, '%b') AS month, SUM(amount) AS total
    FROM transactions
    GROUP BY DATE_FORMAT(transaction_date, '%Y-%m')
    ORDER BY MIN(transaction_date)
");

// Prepare data for Chart.js
$months = [];
$totals = [];
while ($row = $monthlyTransactionQuery->fetch_assoc()) {
    $months[] = $row['month'];  // Month name (e.g., Jan, Feb)
    $totals[] = $row['total'];  // Total amount
}

// Convert to JSON for JavaScript
$monthsJson = json_encode($months);
$totalsJson = json_encode($totals);

// Fetch Total Properties
$totalPropertiesQuery = $conn->query("SELECT COUNT(*) AS total FROM properties");
$totalProperties = $totalPropertiesQuery->fetch_assoc()['total'];

// Fetch Total Transactions
$totalTransactionsQuery = $conn->query("SELECT COUNT(*) AS total FROM transactions");
$totalTransactions = $totalTransactionsQuery->fetch_assoc()['total'];

// Fetch Total Transaction Amount
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
    <style>
        body {
            background-color: #f4f4f4;
            transition: background 0.3s;
        }
        .dark-mode {
            background-color: #212529;
            color: white;
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
        }
        .dark-mode .card {
            background: #343a40;
            color: white;
        }
        #darkModeToggle {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <?php include('layout&others/sidebar.php'); ?>

    <div class="content">
        <h2>Admin Dashboard</h2>
        <span id="darkModeToggle">🌙</span>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h4>Total Properties</h4>
                    <h2 id="propertyCount">0</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h4>Total Transactions</h4>
                    <h2 id="transactionCount">0</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h4>Total Amount Transacted</h4>
                    <h2 id="amountCount">Ksh 0</h2>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3>Transaction Flow</h3>
            <canvas id="transactionChart"></canvas>
        </div>
    </div>

    <script>
        // Dark Mode Persistence
        if (localStorage.getItem("dark-mode") === "enabled") {
            document.body.classList.add("dark-mode");
        }

        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
            localStorage.setItem("dark-mode", document.body.classList.contains("dark-mode") ? "enabled" : "disabled");
        }
        document.getElementById("darkModeToggle").addEventListener("click", toggleDarkMode);

        // Animated Counters
        function animateCounter(id, target) {
            let count = 0;
            const interval = setInterval(() => {
                count += Math.ceil(target / 50);
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                }
                document.getElementById(id).textContent = count;
            }, 20);
        }
        animateCounter("propertyCount", <?php echo $totalProperties; ?>);
        animateCounter("transactionCount", <?php echo $totalTransactions; ?>);
        animateCounter("amountCount", <?php echo $totalTransactionAmount; ?>);

        // Chart.js Line Chart
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
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
