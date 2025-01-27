<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php'; // Include your db_connection.php

$conn = getDbConnection(); // Call the function to establish connection
if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}
?>
