<?php
session_start();
require '../Dbconn/db_connection.php'; // Include the database connection

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get database connection
$pdo = getDatabaseConnection();
$userId = $_SESSION['user_id'];

// Fetch user's total properties
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_shares FROM properties WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$totalShares = $stmt->fetch(PDO::FETCH_ASSOC)['total_shares'] ?? 0;

// Fetch pending balance
$stmt = $pdo->prepare("SELECT SUM(amount) AS pending_balance FROM transactions WHERE user_id = :user_id AND status = 'pending'");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$pendingBalance = $stmt->fetch(PDO::FETCH_ASSOC)['pending_balance'] ?? 0;

// Fetch total amount spent
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_spent FROM transactions WHERE user_id = :user_id AND status = 'completed'");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$totalSpent = $stmt->fetch(PDO::FETCH_ASSOC)['total_spent'] ?? 0;

// Fetch transaction history
$stmt = $pdo->prepare("SELECT amount, payment_date, description FROM transactions WHERE user_id = :user_id ORDER BY payment_date DESC");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
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
</head>
<body>
    <div class="container mt-4">
        <h2>Welcome, <?php echo $_SESSION['fullname']; ?></h2>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Member Shares</h5>
                        <p class="card-text"><?php echo $totalShares; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Balance</h5>
                        <p class="card-text">$<?php echo number_format($pendingBalance, 2); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Spent</h5>
                        <p class="card-text">$<?php echo number_format($totalSpent, 2); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-4">Transaction History</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction) : ?>
                    <tr>
                        <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                        <td><?php echo $transaction['payment_date']; ?></td>
                        <td><?php echo $transaction['description']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
