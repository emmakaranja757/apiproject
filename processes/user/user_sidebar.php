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
        <img src="/apiproject/IMAGES/profile.jpeg" alt="Profile Picture">
        <h3><?php echo htmlspecialchars($name); ?></h3>
        <p>Admin</p>
    </div>

    <ul class="menu">
        <li><a href="admin_dashboard.php">🏠 Dashboard</a></li>
    </ul>

    <a href="logout.php" class="logout-btn">🚪 Logout</a>
</div>

<style>
    /* Sidebar Styling */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #111;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .profile {
        text-align: center;
        margin-bottom: 20px;
    }
    .profile img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
    }
    .profile h3 {
        margin: 10px 0 5px;
        font-size: 18px;
    }
    .profile p {
        font-size: 14px;
        color: #bbb;
    }
    .menu {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }
    .menu li {
        margin: 15px 0;
    }
    .menu a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 5px;
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
        background: #495057;;
        padding: 10px;
        border-radius: 5px;
        margin-top: auto;
        transition: 0.3s;
    }
    .logout-btn:hover {
        background-color : #495057;
    }

    /* Main Content Area */
    .content-wrapper {
        margin-left: 260px; /* Ensures content starts after the sidebar */
        padding: 20px;
    }
</style>
