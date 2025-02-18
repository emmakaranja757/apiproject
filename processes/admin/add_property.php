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
