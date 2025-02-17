<?php
function getDatabaseConnection() {
    $host = 'localhost'; // Change if necessary
    $dbname = 'users'; // Your database name
    $username = 'root'; // Your MySQL username
    $password = '425096'; // Your MySQL password

    try {
        return new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
