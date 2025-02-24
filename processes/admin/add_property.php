<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['email'])) {
    header("Location: admin_login.php");
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "425096", "users");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $property_name = $_POST['property_name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Insert query
    $sql = "INSERT INTO properties (property_name, location, price, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $property_name, $location, $price, $status);

    if ($stmt->execute()) {
        echo "<script>
                alert('Property added successfully!');
                window.location.href = 'properties.php';
              </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Add Property</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Property Name</label>
            <input type="text" class="form-control" name="property_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" class="form-control" name="location" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" name="price" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-control" name="status" required>
                <option value="Available">Available</option>
                <option value="Sold">Sold</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Property</button>
    </form>
</div>
</body>
</html>
