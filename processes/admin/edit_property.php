<?php
include '../../Dbconn/db_connection.php';

// Establish database connection
$conn = getDatabaseConnection();
if (!$conn) {
    die("Database connection failed.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Property</title>
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
        #searchResults {
            display: none;
            position: absolute;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ccc;
        }
        .search-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }
        .search-item:hover {
            background: #f8f9fa;
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
                <label for="searchProperty" class="form-label">Enter Property Name, Location, or Price</label>
                <input type="text" class="form-control" id="searchProperty" name="search_property" required>
            </div>
            <button type="submit" class="btn btn-custom">Search</button>
        </form>
        <div id="searchResults" class="dropdown-menu"></div>
    </div>
</div>



</body>
</html>
