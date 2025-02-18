<?php
include '../../Dbconn/db_connection.php';

// Get the property ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $property_id = intval($_GET['id']);
} else {
    die("Invalid property ID.");
}

// Fetch property details for editing
$conn = getDatabaseConnection();
if (!$conn) {
    die("Database connection failed.");
}

$sql = "SELECT * FROM properties WHERE property_id = :property_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':property_id', $property_id);
$stmt->execute();
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

 <!-- Sidebar -->
    
 <?php include('layout/sidebar.php'); ?>
    <!-- Content Area -->
    <div class="content">
        <div class="form-container">
            <h3>Edit Property</h3>
<form action="edit_property.php?id=<?php echo htmlspecialchars($property['property_id']); ?>" method="POST">
    <div class="mb-3">
        <label for="propertyName" class="form-label">Property Name</label>
        <input type="text" class="form-control" id="propertyName" name="property_name" value="<?php echo htmlspecialchars($property['property_name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($property['location'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($property['price'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="Available" <?php echo ($property['status'] == 'Available' ? 'selected' : ''); ?>>Available</option>
            <option value="Sold" <?php echo ($property['status'] == 'Sold' ? 'selected' : ''); ?>>Sold</option>
            <option value="Pending" <?php echo ($property['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
        </select>
    </div>
    <button type="submit" class="btn btn-warning">Update Property</button>
</form>
