<?php
include('../../Dbconn/db_connection.php');

function updateProperty() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $property_id = $_POST['property_id']; // Ensure the ID of the property is provided
        $column = $_POST['column']; // The column to update (e.g., 'price', 'status')
        $new_value = $_POST['new_value']; // The new value for the selected column

        $conn = getDatabaseConnection();
        
        // Validate the column input to prevent SQL injection
        $allowed_columns = ['property_name', 'location', 'price', 'status'];
        if (!in_array($column, $allowed_columns)) {
            die("Invalid column selected.");
        }

        try {
            // Prepare and execute the update query
            $sql = "UPDATE properties SET $column = :new_value WHERE property_id = :property_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':new_value', $new_value, PDO::PARAM_STR);
            $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Property updated successfully.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error updating property.</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Invalid request method.</div>";
    }
}

function displayProperties() {
    $conn = getDatabaseConnection();
    $sql = "SELECT property_id, property_name, location, price, status, created_at, info_id FROM properties";
    $stmt = $conn->query($sql);
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table class='table table-bordered'>";
    echo "<tr><th>ID</th><th>Name</th><th>Location</th><th>Price</th><th>Status</th><th>Created At</th><th>Info ID</th></tr>";
    foreach ($properties as $property) {
        echo "<tr>";
        echo "<td>{$property['property_id']}</td>";
        echo "<td>{$property['property_name']}</td>";
        echo "<td>{$property['location']}</td>";
        echo "<td>{$property['price']}</td>";
        echo "<td>{$property['status']}</td>";
        echo "<td>{$property['created_at']}</td>";
        echo "<td>{$property['info_id']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
