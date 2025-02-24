<?php
session_start();
require_once '../../Dbconn/db_connection.php';
require_once 'Crude.php';  // Ensure this file contains necessary functions

$conn = getDatabaseConnection();
if (!$conn) {
    die("Database connection failed.");
}

// Check if property_id exists in session
if (!isset($_SESSION['property_id'])) {
    die("No property selected.");
}

$property_id = $_SESSION['property_id'];

// Fetch property details for the specific property
$stmt = $conn->prepare("SELECT * FROM properties WHERE property_id = :property_id");
$stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
$stmt->execute();
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    die("Property not found.");
}

// Process the form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Validate inputs
    $property_name = isset($_POST['property_name']) ? trim($_POST['property_name']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if (empty($property_name) || empty($location) || empty($price) || empty($status)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Convert price to a float to prevent data truncation errors
        $price = floatval($price);

        // Update query
        $stmt = $conn->prepare("UPDATE properties 
                                SET property_name = :property_name, 
                                    location = :location, 
                                    price = :price, 
                                    status = :status 
                                WHERE property_id = :property_id");
        $stmt->bindParam(':property_name', $property_name);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Property updated successfully!'); window.location.href='properties.php';</script>";
        } else {
            echo "<script>alert('Failed to update property.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit Property</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #343a40;
            padding-top: 20px;
            color: white;
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
    </style>
</head>
<body>
    <?php include('layout&others/sidebar.php'); ?>
    
    <div class="content">
        <h2>Edit Property: <?php echo htmlspecialchars($property['property_name']); ?></h2>

        <!-- Update Property Form (Displays only selected property data) -->
        <form method="POST" action="edit_property.php">
            <!-- Hidden field for property_id -->
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">

            <div class="mb-3">
                <label for="property_name" class="form-label">Property Name</label>
                <input type="text" name="property_name" class="form-control" value="<?php echo htmlspecialchars($property['property_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($property['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" name="price" class="form-control" value="<?php echo htmlspecialchars($property['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" name="status" class="form-control" value="<?php echo htmlspecialchars($property['status']); ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Property</button>
        </form>
    </div>
</body>
</html>
