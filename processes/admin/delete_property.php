<?php
session_start();
include '../../Dbconn/db_connection.php';

$conn = getDatabaseConnection();

// ✅ Check if property_id is provided
$property_id = $_GET['id'] ?? $_SESSION['property_id'] ?? null;

if (!$property_id) {
    echo "<script>alert('No property selected. Redirecting...'); window.location.href='properties.php';</script>";
    exit();
}


// ✅ Fetch property details
$sql = "SELECT * FROM properties WHERE property_id = :property_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':property_id', $property_id);
$stmt->execute();
$property = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ If property not found, redirect
if (!$property) {
    echo "<script>alert('Property not found. Redirecting...'); window.location.href='properties.php';</script>";
    exit();
}

// ✅ Handle delete confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    $delete_sql = "DELETE FROM properties WHERE property_id = :property_id";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bindParam(':property_id', $property_id);

    try {
        $delete_stmt->execute();
        echo "<script>alert('Property deleted successfully!'); window.location.href='properties.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error deleting property: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Property</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn-danger {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Confirm Delete</h3>
    <p>Are you sure you want to delete this property?</p>

    <table class="table table-bordered">
        <tr><th>ID</th><td><?= htmlspecialchars($property['property_id']) ?></td></tr>
        <tr><th>Name</th><td><?= htmlspecialchars($property['property_name']) ?></td></tr>
        <tr><th>Location</th><td><?= htmlspecialchars($property['location']) ?></td></tr>
        <tr><th>Price</th><td>Ksh<?= number_format($property['price'], 2) ?></td></tr>
        <tr><th>Status</th><td><?= htmlspecialchars($property['status']) ?></td></tr>
        <tr><th>Created At</th><td><?= htmlspecialchars($property['created_at']) ?></td></tr>
    </table>

    <form method="POST">
        <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
        <a href="properties.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
