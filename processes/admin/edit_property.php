<?php
include('Crude.php');


include '../../Dbconn/db_connection.php';

$conn = getDatabaseConnection();
if (!$conn) {
    die("Database connection failed.");
}

// Retrieve the property_id from session
if (!isset($_SESSION['property_id'])) {
    die("No property selected.");
}

$property_id = $_SESSION['property_id'];

// Fetch property details from the database
$stmt = $conn->prepare("SELECT * FROM properties WHERE property_id = :property_id");
$stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
$stmt->execute();
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    die("Property not found.");
}

// Display property details for editing
echo "<h2>Edit Property: " . htmlspecialchars($property['property_name']) . "</h2>";
?>

?>
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

    </style>
</head>
<body>
    <?php include('layout&others/sidebar.php'); ?>
    
    <div class="container mt-4">
        <h2>Property List</h2>
        <?php displayProperties(); ?>
        <h2 class="mt-4">Update Property</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="property_id" class="form-label">Property ID</label>
                <input type="number" name="property_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="column" class="form-label">Column to Update</label>
                <select name="column" class="form-control" required>
                    <option value="property_name">Property Name</option>
                    <option value="location">Location</option>
                    <option value="price">Price</option>
                    <option value="status">Status</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="new_value" class="form-label">New Value</label>
                <input type="text" name="new_value" class="form-control" required>
            </div>
            <button type="submit" onclick="<?php updateProperty()?>"class="btn btn-primary">Update Property</button>
        </form>
    </div>


    </body>
    </html>