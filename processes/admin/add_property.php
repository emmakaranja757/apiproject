<?php
include '../../Dbconn/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $property_name = $_POST['property_name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $conn = getDatabaseConnection();

    $sql = "INSERT INTO properties (property_name, location, price, status) 
            VALUES (:property_name, :location, :price, :status)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':property_name', $property_name);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':status', $status);

    try {
        $stmt->execute();
        header("Location: admin_dashboard.php"); // Redirect back to dashboard
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Add Property Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        .sidebar a:last-child {
            margin-top: auto; /* Push logout link to the bottom */
            background: rgb(72, 62, 63); /* Red color for logout */
            color: white;
        }
        .sidebar a:last-child:hover {
            background: rgb(86, 71, 72); /* Darker red when hovered */
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
        .form-container {
            margin-top: 30px;
        }
        .form-container h3 {
            margin-bottom: 20px;
        }
        .form-container .form-label {
            font-weight: bold;
        }
        .form-container .form-control {
            padding: 10px;
        }
        .form-container .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .form-container .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    
    <?php include('layout/sidebar.php'); ?>
    <!-- Content Area -->
    <div class="content">
        <div class="form-container">
            <h3>Add New Property</h3>
            <form action="add_property.php" method="POST">
                <div class="card p-4">
                    <div class="mb-3">
                        <label for="propertyName" class="form-label">Property Name</label>
                        <input type="text" class="form-control" id="propertyName" name="property_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Available">Available</option>
                            <option value="Sold">Sold</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Property</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
