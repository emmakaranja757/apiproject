<?php
session_start(); // ✅ Start session to store property_id and action

include '../../Dbconn/db_connection.php';

$conn = getDatabaseConnection();
if (!$conn) {
    die("Database connection failed.");
}

// ✅ Capture the action from the URL and store it in session
if (isset($_GET['action'])) {
    $_SESSION['action'] = $_GET['action'];
}

// ✅ Capture the property_id from the URL and store it in session
if (isset($_GET['property_id'])) {
    $_SESSION['property_id'] = $_GET['property_id'];

    // ✅ Redirect based on the stored action
    if ($_SESSION['action'] == 1) {
        header("Location: add_property.php");
    } elseif ($_SESSION['action'] == 2) {
        header("Location: edit_property.php");
    } elseif ($_SESSION['action'] == 3) {
        header("Location: delete_property.php");
    }
    exit();
}

// ✅ Handle the form submission for searching a property
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_property'])) {
    $search_property = $conn->real_escape_string($_POST['search_property']);

    // Query to search property by name, location, or price
    $query = "SELECT property_id FROM properties 
              WHERE property_name LIKE '%$search_property%' 
              OR location LIKE '%$search_property%' 
              OR price LIKE '%$search_property%' 
              LIMIT 1";

    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        $_SESSION['property_id'] = $row['property_id'];

        // ✅ Redirect based on stored action
        if ($_SESSION['action'] == 1) {
            header("Location: add_property.php?id=" . ($_SESSION['property_id'] ?? ''));
        } elseif ($_SESSION['action'] == 2 && isset($_SESSION['property_id'])) {
            header("Location: edit_property.php?id=" . $_SESSION['property_id']);
        }
        } elseif ($_SESSION['action'] == 3 && isset($_SESSION['property_id'])) {
            header("Location: delete_property.php?id=" . $_SESSION['property_id']); // Pass ID correctly
        }
        
        exit();
    } else {
        echo "<script>alert('Property not found. Please try again.');</script>";
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
            position: relative;
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
        /* 🔹 Fix Dropdown Position */
        .search-container {
            position: relative;
        }
        #searchResults {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ccc;
            z-index: 1000;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
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

<?php include('layout&others/sidebar.php'); ?>

<!-- Content Area -->
<div class="content">
    <div class="form-container">
        <h3>Search Property</h3>
        <form method="POST">
            <div class="mb-3 search-container">
                <label for="searchProperty" class="form-label">Enter Property Name, Location, or Price</label>
                <input type="text" class="form-control" id="searchProperty" name="search_property" autocomplete="off" required>
                <div id="searchResults" class="dropdown-menu"></div> <!-- 🔹 Dropdown placed inside search-container -->
            </div>
            <button type="submit" class="btn btn-custom">Search</button>
        </form>
    </div>
</div>

<!-- jQuery & AJAX Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="layout&others/Asearch.js"></script>

</body>
</html>
