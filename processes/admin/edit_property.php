<?php
include('Crude.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f4f4;
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
        .sidebar a:last-child {
            margin-top: auto; /* Push logout link to the bottom */
            background: rgb(72, 62, 63); /* Red color for logout */
            color: white;
        }
        .sidebar a:last-child:hover {
            background: rgb(86, 71, 72); /* Darker red when hovered */
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .card {
             text-align: center;
             font-size: 18px;
             font-weight: bold;
             background: #ffffff;
             border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

    </style>
</head>
<body>
    <?php include('layout&others/sidebar.php'); ?>
    </body>
    </html>