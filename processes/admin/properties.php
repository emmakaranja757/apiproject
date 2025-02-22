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

// Fetch Properties
$properties = $conn->query("SELECT property_id, property_name, location, price, status, created_at FROM properties");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties</title>
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
    </style>
</head>
<body>

<div class="container">
    <button class="btn btn-dark btn-back" onclick="location.href='admin_dashboard.php'">Back to Dashboard</button>

    <h2>Properties</h2>
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
                    <td>Ksh<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <button class="btn btn-secondary" onclick="location.href='add_property.php'">Add Property</button>
    <button class="btn btn-secondary" onclick="location.href='FilterSearch.php'">Edit Property</button>
    <button class="btn btn-secondary" onclick="location.href='delete_property.php'">Delete Property</button>
</div>

</body>
</html>
