<?php
// Function to establish and return a PDO database connection
    // Database configuration
    $host = 'localhost'; // Database host (usually localhost)
    $dbname = 'users'; // Replace with your database name
    $username = 'root'; // Replace with your MySQL username
    $password = ''; // Replace with your MySQL password (leave empty if none)

    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo; // Return the connection
    } catch (PDOException $e) {
        // Display error message and terminate script
        die("Database connection failed: " . $e->getMessage());
    }

?>
