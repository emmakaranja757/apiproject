<?php
session_start();
error_reporting(E_ALL);
include('../../Dbconn/db_connection.php'); // Ensure this file exists and connects properly

$pdo = getDatabaseConnection(); // Ensure this function returns a valid PDO object

// If already logged in, redirect to admin dashboard
if (isset($_SESSION['email'])) {
    header("Location:admin_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check email
    $stmt = $pdo->prepare("SELECT * FROM info WHERE Email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        // Verify the hashed password
        if (password_verify($password, $admin['Password'])) {
            // Store admin email in session
            $_SESSION['email'] = $admin['Email'];

            // Redirect to dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid email or password!";
        }
    } else {
        $error_message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../../IMAGES/background.jpeg') no-repeat center center fixed;
            background-size: cover;
            transition: background-color 0.3s ease;
        }
        
        .login-container {
            width: 400px;
            margin: 100px auto;
            padding: 25px;
            border: 2px solid #000;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
        }
        
        h2 {
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .btn-primary {
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: scale(1.05);
        }
        
        .forgot-password {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s ease;
        }
        .forgot-password:hover {
            color: #0056b3;
        }
        
        /* Dark Mode */
        .dark-mode {
            background-color: #121212;
            color: #fff;
        }
        .dark-mode .login-container {
            background: rgba(30, 30, 30, 0.9);
            border-color: #fff;
        }
        .dark-mode h2 {
            color: #f8f9fa;
        }
        .dark-mode .forgot-password {
            color: #bb86fc;
        }
        .dark-mode .btn-primary {
            background-color: #bb86fc;
            border-color: #bb86fc;
        }
        
        /* Dark Mode Toggle Button */
        .dark-mode-toggle {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <span id="darkModeToggle" class="dark-mode-toggle">🌙</span>
        <h2>Admin Login</h2>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="admin_login.php" method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <a href="forget_password.php" class="forgot-password">Forgot Password?</a>
        </form>
    </div>

    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
            darkModeToggle.textContent = document.body.classList.contains('dark-mode') ? '☀️' : '🌙';
        });

        // Load dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
            darkModeToggle.textContent = '☀️';
        }
    </script>
</body>
</html>
