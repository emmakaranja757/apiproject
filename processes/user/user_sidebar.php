<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../Dbconn/db_connection.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$pdo = getDatabaseConnection();
$email = $_SESSION['email'];

// Fetch user details
$stmt = $pdo->prepare("SELECT info_id, fullname FROM info WHERE Email = :email");
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$userId = $user['info_id'];
$name = $user['fullname'];
?>

<div class="sidebar">
    <div class="profile">
        <img src="../../uploads/default.png" alt="Profile Picture">
        <h3><?php echo htmlspecialchars($name); ?></h3>
        <p>User</p>
    </div>

    <ul class="menu">
        <li><a href="user_dashboard.php">🏠 Dashboard</a></li>
        <li><a href="transactions.php">💰 Transactions</a></li>
        <li><a href="settings.php">⚙️ Settings</a></li>
    </ul>

    <a href="logout.php" class="logout-btn">🚪 Log Out</a>
</div>

<style>
    .sidebar {
        width: 220px;
        height: 100vh;
        background: #000;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        padding: 15px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .profile {
        text-align: center;
        margin-bottom: 10px;
    }
    .profile img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
    }
    .profile h3 {
        margin: 8px 0 4px;
        font-size: 16px;
    }
    .profile p {
        font-size: 12px;
        color: #bbb;
        margin-bottom: 5px;
    }
    .menu {
        list-style: none;
        padding: 0;
        margin-top: 10px;
    }
    .menu li {
        margin: 10px 0;
    }
    .menu a {
        color: white;
        text-decoration: none;
        font-size: 14px;
        display: block;
        padding: 8px;
        border-radius: 4px;
        transition: 0.3s;
    }
    .menu a:hover {
        background: #222;
    }
    .logout-btn {
        display: block;
        text-align: center;
        color: white;
        text-decoration: none;
        background: #d9534f;
        padding: 8px;
        border-radius: 4px;
        transition: 0.3s;
    }
    .logout-btn:hover {
        background: #c9302c;
    }
</style>
