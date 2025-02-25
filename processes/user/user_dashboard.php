<?php
session_start();
require '../../Dbconn/db_connection.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$pdo = getDatabaseConnection();
$email = $_SESSION['email'];

// Fetch user details
$stmt = $pdo->prepare("SELECT info_id, fullname FROM info WHERE Email = :email");
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$userId = $user['info_id'];
$name = $user['fullname'];

// Fetch user stats
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_shares FROM properties WHERE info_id = :info_id");
$stmt->bindParam(':info_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$totalShares = $stmt->fetch(PDO::FETCH_ASSOC)['total_shares'] ?? 0;

$stmt = $pdo->prepare("SELECT SUM(amount) AS balance FROM transactions WHERE info_id = :info_id");
$stmt->bindParam(':info_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$pendingBalance = $stmt->fetch(PDO::FETCH_ASSOC)['balance'] ?? 0;

$stmt = $pdo->prepare("
    SELECT SUM(t.amount) AS total_spent 
    FROM transactions t
    JOIN payments p ON t.transaction_id = p.transaction_id
    WHERE t.info_id = :info_id AND p.status = 'completed'
");
$stmt->bindParam(':info_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$totalSpent = $stmt->fetch(PDO::FETCH_ASSOC)['total_spent'] ?? 0;

// Fetch transaction history
$stmt = $pdo->prepare("
    SELECT t.amount, p.payment_date, 'Transaction' AS description 
    FROM transactions t
    LEFT JOIN payments p ON t.transaction_id = p.transaction_id
    WHERE t.info_id = :info_id 
    ORDER BY p.payment_date DESC
");
$stmt->bindParam(':info_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            transition: background 0.3s, color 0.3s;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
        }
        .card.bg-danger {
            background-color: #ffebee !important;
            color: #b71c1c !important;
        }
        .bg-light-blue { background-color: #E3F2FD; color: #0D47A1; }
        .bg-light-green { background-color: #E8F5E9; color: #1B5E20; }
        .bg-light-purple { background-color: #F3E5F5; color: #6A1B9A; }
        .dark-mode {
            background-color: #121212;
            color: white;
        }
        .dark-mode .card {
            background-color: #1e1e1e;
            color: white;
        }
        .dark-mode .table {
            color: white;
        }
        .btn-dark-mode {
            background-color: #ffa000;
            color: black;
            border: none;
        }
    </style>
</head>
<body>
<body>
    <div class="container-fluid">
        <div class="row vh-100">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 bg-light position-fixed vh-100 overflow-hidden">
                <?php include 'user_sidebar.php'; ?>
            </div>

            <!-- Main content area -->
            <div class="col-md-10 offset-md-2 px-3 mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>
                    <button class="btn btn-dark-mode" id="darkModeToggle">Dark Mode</button>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card bg-light-blue">
                            <h5 class="card-title">Member Shares</h5>
                            <h2><?php echo $totalShares; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card <?php echo ($pendingBalance > 0) ? 'bg-danger' : 'bg-light-green'; ?>">
                            <h5 class="card-title">Pending Balance</h5>
                            <h2>Ksh <?php echo number_format($pendingBalance, 2); ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light-purple">
                            <h5 class="card-title">Total Spent</h5>
                            <h2>Ksh <?php echo number_format($totalSpent, 2); ?></h2>
                        </div>
                    </div>
                </div>

                <h3 class="mt-4">Transaction History</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction) : ?>
                                <tr>
                                    <td>Ksh <?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td><?php echo $transaction['payment_date']; ?></td>
                                    <td><?php echo $transaction['description']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div> <!-- End of col-md-10 -->
        </div> <!-- End of row -->
    </div> <!-- End of container-fluid -->
  <script>
        // Dark Mode Toggle
        const toggleButton = document.getElementById('darkModeToggle');
        const body = document.body;

        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
        }

        toggleButton.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        });
    </script>
</body>
</html>
