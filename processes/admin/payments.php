<?php
session_start();
include '../../Dbconn/db_connection.php'; // Ensure this connects to your database

// Check if the admin is logged in
if (!isset($_SESSION['email'])) {
    header("Location: admin_login.php");
    exit();
}

// Get database connection
$conn = getDatabaseConnection();

// Fetch payments from the database
$sql = "SELECT py.payment_id, i.fullname AS payer, t.transaction_id, py.amount, py.payment_method, py.status, py.payment_date
        FROM payments py
        JOIN transactions t ON py.transaction_id = t.transaction_id
        JOIN info i ON py.info_id = i.info_id
        ORDER BY py.payment_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-back {
            margin-bottom: 15px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 2px solid #000;
            text-align: left;
        }
        .table-dark {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <button class="btn btn-dark btn-back" onclick="location.href='admin_dashboard.php'">Back to Dashboard</button>
    
    <h2>Payments</h2>
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
            <?php
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$row['payment_id']}</td>
                        <td>{$row['payer']}</td>
                        <td>{$row['transaction_id']}</td>
                        <td>Ksh" . number_format($row['amount'], 2) . "</td>
                        <td>{$row['payment_method']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['payment_date']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No payments found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$conn = null;
?>
