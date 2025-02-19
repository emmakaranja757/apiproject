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

        // Prepare and execute the update query
        $sql = "UPDATE properties SET $column = :new_value WHERE id = :property_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':new_value', $new_value);
        $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Property updated successfully.";
        } else {
            echo "Error updating property.";
        }
    } else {
        echo "Invalid request method.";
    }
}

updateProperty();
?>
