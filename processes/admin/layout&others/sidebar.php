<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "425096", "users");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details from "info" table
$email = $_SESSION['email'];
$query = $conn->query("SELECT fullname FROM info WHERE Email='$email'");
$user = $query->fetch_assoc();
$fullname = $user['fullname'] ?? 'Admin';

$conn->close();
?>

<div class="sidebar">
    <div class="profile">
    <img src="/apiproject/IMAGES/profile.jpeg" alt="Profile Picture">

        <p><?php echo htmlspecialchars($fullname); ?></p>
    </div>
    <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a>
    <a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a>
    <a href="properties.php"><i class="fas fa-building"></i> Properties</a>
    <a href="add_property.php"><i class="fas fa-plus-circle"></i> Add Property</a>
    <a href="edit_property.php"><i class="fas fa-edit"></i> Edit Property</a>
    
    <a href="admin_logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<style>
    .sidebar {
        width: 200px;
        height: 100vh;
        background-color: #343a40;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 20px;
        display: flex;
        flex-direction: column;
    }

    .profile {
        text-align: center;
        padding: 10px;
        margin-bottom: 10px;
    }

    .profile img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .profile p {
        margin-top: 10px;
        font-weight: bold;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        color: white;
        padding: 10px;
        text-decoration: none;
        transition: background 0.3s;
    }

    .sidebar a i {
        margin-right: 10px;
    }

    .sidebar a:hover {
        background-color: #495057;
    }

    .logout {
        margin-top: auto;
        text-align: center;
    }

    .logout:hover {
        background-color: #495057;
    }
</style>

<!-- Include FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
