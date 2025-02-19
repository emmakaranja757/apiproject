<?php
include '../../Dbconn/db_connection.php';

// Establish database connection
$conn = getDatabaseConnection();
if (!$conn) {
    die("Database connection failed.");
}

$property = null; // Initialize property variable

// If the search form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_property'])) {
    $search_name = trim($_POST['search_property']);

    $sql = "SELECT * FROM properties WHERE property_name LIKE :search_name LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':search_name', "%$search_name%");
    $stmt->execute();
    $property = $stmt->fetch(PDO::FETCH_ASSOC);
}

// If the update form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_property'])) {
    if (!isset($_POST['property_id'])) {
        die("No property selected for update.");
    }

    $property_id = intval($_POST['property_id']);
    $sql = "UPDATE properties SET ";
    $updates = [];
    $params = [];

    if (!empty($_POST['property_name'])) {
        $updates[] = "property_name = :property_name";
        $params[':property_name'] = $_POST['property_name'];
    }
    if (!empty($_POST['location'])) {
        $updates[] = "location = :location";
        $params[':location'] = $_POST['location'];
    }
    if (!empty($_POST['price'])) {
        $updates[] = "price = :price";
        $params[':price'] = $_POST['price'];
    }
    if (!empty($_POST['status'])) {
        $updates[] = "status = :status";
        $params[':status'] = $_POST['status'];
    }

    if (empty($updates)) {
        die("No updates provided.");
    }

    $sql .= implode(", ", $updates) . " WHERE property_id = :property_id";
    $params[':property_id'] = $property_id;

    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute($params);
        header("Location: ../../admin_dashboard.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #ffcc00;
            color: black;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-custom:hover {
            background-color: #e6b800;
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

<?php include('layout/sidebar.php'); ?>

<!-- Content Area -->
<div class="content">
    <div class="form-container">
        <h3>Search Property</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="searchProperty" class="form-label">Enter Property Name</label>
                <input type="text" class="form-control" id="searchProperty" name="search_property" required>
            </div>
            <button type="submit" class="btn btn-custom">Search</button>
        </form>
    </div>

    <?php if ($property): ?>
    <div class="form-container mt-4">
        <h3>Edit Property</h3>
        <form method="POST">
            <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($property['property_id']); ?>">
            <div class="mb-3">
                <label for="propertyName" class="form-label">Property Name</label>
                <input type="text" class="form-control" id="propertyName" name="property_name" value="<?php echo htmlspecialchars($property['property_name']); ?>">
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($property['location']); ?>">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($property['price']); ?>">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="Available" <?php echo ($property['status'] == 'Available' ? 'selected' : ''); ?>>Available</option>
                    <option value="Sold" <?php echo ($property['status'] == 'Sold' ? 'selected' : ''); ?>>Sold</option>
                    <option value="Pending" <?php echo ($property['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                </select>
            </div>
            <button type="submit" name="update_property" class="btn btn-warning">Update Property</button>
        </form>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
