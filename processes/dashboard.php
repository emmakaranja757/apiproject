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
        <a href="#users">User Management</a>
        <a href="#properties">Properties</a>
        <a href="#transactions">Transactions</a>
        <a href="#settings">Settings</a>
    </div>

    <div class="content">
        <h2>Admin Dashboard</h2>
        
        <!-- System Overview -->
        <div id="overview" class="row">
            <div class="col-md-3">
                <div class="card p-3">
                    <h5>Total Users</h5>
                    <h3>1,234</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h5>Total Properties</h5>
                    <h3>567</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h5>Total Transactions</h5>
                    <h3>89</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h5>Total Revenue</h5>
                    <h3>$120,000</h3>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-4">
            <button class="btn btn-outline-primary">Add Property</button>
            <button class="btn btn-outline-secondary">Manage Users</button>
            <button class="btn btn-outline-dark">Track Memberships</button>
        </div>
        
        <!-- Recent Activity -->
        <div class="mt-4">
            <h4>Recent Activity</h4>
            <ul class="list-group">
                <li class="list-group-item">New user registered - John Doe</li>
                <li class="list-group-item">Property "Luxury Villa" sold for $500,000</li>
                <li class="list-group-item">Transaction completed: $12,500</li>
            </ul>
        </div>
        
        <!-- Transactions Section -->
        <div id="transactions" class="mt-5">
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
