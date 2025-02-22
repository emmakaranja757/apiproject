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

// Fetch Transactions
$sql = "
    SELECT t.transaction_id, p.property_name, i.fullname AS buyer, t.amount, t.transaction_date
    FROM transactions t
    JOIN properties p ON t.property_id = p.property_id
    JOIN info i ON t.info_id = i.info_id
    ORDER BY t.transaction_date DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file -->
</head>
<body>

<div class="main-content">
    <h2>Transactions</h2>
    
    <table border="1">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Property</th>
                <th>Buyer</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($transactions)) {
                foreach ($transactions as $row) {
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

    <!-- Back Button -->
    <button onclick="window.location.href='admin_dashboard.php'" class="back-btn">Back</button>
</div>

<style>
    .main-content {
        margin-left: 220px; /* Adjust for sidebar */
        padding: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f4f4f4;
    }

    .back-btn {
        margin-top: 20px;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #333;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .back-btn:hover {
        background-color: #555;
    }
</style>

</body>
</html>

<?php
$conn = null;
?>
