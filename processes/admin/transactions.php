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

// Fetch transactions from the database
$sql = "SELECT t.transaction_id, p.property_name, i.fullname AS buyer, t.amount, t.transaction_date
        FROM transactions t
        JOIN properties p ON t.property_id = p.property_id
        JOIN info i ON t.info_id = i.info_id
        ORDER BY t.transaction_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
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
    
    <h2>Transactions</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Property</th>
                <th>Buyer</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$row['transaction_id']}</td>
                        <td>{$row['property_name']}</td>
                        <td>{$row['buyer']}</td>
                        <td>Ksh" . number_format($row['amount'], 2) . "</td>
                        <td>{$row['transaction_date']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No transactions found</td></tr>";
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
