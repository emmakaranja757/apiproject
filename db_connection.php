<?php
// db_connection.php
function getDbConnection() {
    $host = 'localhost';
    $dbname = 'users';
    $username_db = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("<div class='alert alert-danger mt-3'>Database Connection Failed: " . $e->getMessage() . "</div>");
    }
}
?>