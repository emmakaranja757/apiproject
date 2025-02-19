<?php
include '../../Dbconn/db_connection.php';

header("Content-Type: application/json");

$conn = getDatabaseConnection();
if (!$conn) {
    echo json_encode([]);
    exit();
}

$query = isset($_GET['query']) ? trim($_GET['query']) : "";
if ($query === "") {
    echo json_encode([]);
    exit();
}

$sql = "SELECT property_id, property_name, location, price 
        FROM properties 
        WHERE property_name LIKE :query 
        OR location LIKE :query 
        OR price LIKE :query 
        LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":query", "%$query%");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>
