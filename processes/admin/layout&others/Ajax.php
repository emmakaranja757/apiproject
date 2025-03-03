<?php
// Start session to store property_id

// Correct database connection path
include '../../../Dbconn/db_connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = getDatabaseConnection();
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Ensure 'query' parameter is received
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = trim($_GET['query']);

    try {
        // Prepare the query using PDO
        $stmt = $conn->prepare("SELECT property_id, property_name, location, price FROM properties 
                                WHERE property_name LIKE :query 
                                OR location LIKE :query 
                                OR CAST(price AS CHAR) LIKE :query 
                                LIMIT 10");

        // Bind parameters safely
        $searchTerm = "%$query%";
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch results as an associative array
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Store the first property_id in session (modify logic as needed)
        if (!empty($properties)) {
            $_SESSION['property_id'] = $properties[0]['property_id']; // Storing the first result's property_id
        }

        // Output JSON response
        echo json_encode($properties);
        exit;
    } catch (PDOException $e) {
        echo json_encode(["error" => "SQL Error: " . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode([]);
    exit;
}
?>
