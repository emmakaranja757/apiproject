<?php
include '../../Dbconn/db_connection.php';

// Get the property ID from the URL
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    $conn = getDatabaseConnection();
    $sql = "DELETE FROM properties WHERE property_id = :property_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':property_id', $property_id);

    try {
        $stmt->execute();
        header("Location: admin_dashboard.php"); // Redirect back to dashboard
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
