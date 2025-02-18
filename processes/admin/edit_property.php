<?php
include '../../Dbconn/db_connection.php';

// Get the property ID from the URL
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // Fetch property details for editing
    $conn = getDatabaseConnection();
    $sql = "SELECT * FROM properties WHERE property_id = :property_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':property_id', $property_id);
    $stmt->execute();

    $property = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update property if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create the SQL query
    $sql = "UPDATE properties SET ";
    $updates = [];
    $params = [];

    // Check if each field is set and add it to the query
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

    // Join the updates array and complete the SQL query
    $sql .= implode(", ", $updates) . " WHERE property_id = :property_id";
    $params[':property_id'] = $property_id;  // Ensure the correct property is updated

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute($params);
        header("Location: admin_dashboard.php"); // Redirect back to dashboard
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Form for editing the property -->
<form action="edit_property.php?id=<?php echo $property['property_id']; ?>" method="POST">
    <div class="mb-3">
        <label for="propertyName" class="form-label">Property Name</label>
        <input type="text" class="form-control" id="propertyName" name="property_name" 
               value="<?php echo htmlspecialchars($property['property_name']); ?>" >
    </div>
    <div class="mb-3">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" id="location" name="location" 
               value="<?php echo htmlspecialchars($property['location']); ?>" >
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" 
               value="<?php echo htmlspecialchars($property['price']); ?>" >
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
