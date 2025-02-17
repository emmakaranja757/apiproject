<?php
// Connect to database
$conn = new mysqli("localhost", "root", "425096", "users");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Properties
$properties = $conn->query("
    SELECT property_id, property_name, location, price, status, created_at
    FROM properties
");

// Fetch Transactions
$transactions = $conn->query("
    SELECT t.transaction_id, p.property_name, i.fullname AS buyer, t.amount, t.transaction_date
    FROM transactions t
    JOIN properties p ON t.property_id = p.property_id
    JOIN info i ON t.info_id = i.info_id
");

// Fetch Payments
$payments = $conn->query("
    SELECT py.payment_id, i.fullname AS payer, t.transaction_id, py.amount, py.payment_method, py.status, py.payment_date
    FROM payments py
    JOIN transactions t ON py.transaction_id = t.transaction_id
    JOIN info i ON py.info_id = i.info_id
");

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
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            color: black;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#overview">Dashboard</a>
        <a href="#transactions">Transactions</a>
        <a href="#payments">Payments</a>
        <a href="#properties">Properties</a>
    </div>

    <div class="content">
        <h2>Admin Dashboard</h2>

        <!-- Properties Table -->
        <div id="properties">
            <h3>Properties</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $properties->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['property_id']; ?></td>
                            <td><?php echo $row['property_name']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td>$<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Transactions Table -->
        <div id="transactions">
            <h3>Transactions</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Property</th>
                        <th>Buyer</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $transactions->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['transaction_id']; ?></td>
                            <td><?php echo $row['property_name']; ?></td>
                            <td><?php echo $row['buyer']; ?></td>
                            <td>$<?php echo number_format($row['amount'], 2); ?></td>
                            <td><?php echo $row['transaction_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Payments Table -->
        <div id="payments" class="mt-5">
            <h3>Payments</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Payment ID</th>
                        <th>Payer</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $payments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['payment_id']; ?></td>
                            <td><?php echo $row['payer']; ?></td>
                            <td><?php echo $row['transaction_id']; ?></td>
                            <td>$<?php echo number_format($row['amount'], 2); ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Transactions Chart -->
        <div class="mt-5">
            <h3>Transaction Flow</h3>
            <canvas id="transactionChart"></canvas>
        </div>
    </div>
    
    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Transaction Flow',
                    data: [1200, 1500, 1100, 1900, 2500, 2200],
                    borderColor: 'black',
                    borderWidth: 2,
                    fill: false
                }]
            }
        });
    </script>
</body>
</html>