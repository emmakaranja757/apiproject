<?php
session_start(); // Start session to access stored email

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['email'])) {
        echo "<script>alert('Session expired! Please request a new reset code.');</script>";
        exit();
    }

    $email = $_SESSION['email']; // Retrieve email from session
    $new_password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['cpassword']));

    // Validate password strength
    if (strlen($new_password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long!');</script>";
    } elseif ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        try {
            // Database connection with error handling
            $pdo = new PDO("mysql:host=localhost;dbname=users", "root", "425096", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // Hash the new password before storing it
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the user's password in the database
            $stmt = $pdo->prepare("UPDATE info SET password = :password WHERE email = :email");
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Clear the reset code
            $stmt = $pdo->prepare("UPDATE info SET reset_code = NULL, code_timestamp = NULL WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Destroy session after password reset
            session_destroy();

            // Show success message in alert & redirect
            echo "<script>
                    alert('Your password has been successfully updated!');
                    window.location.href = 'login.php';
                  </script>";
            exit();

        } catch (PDOException $e) {
            echo "<script>alert('Database Error: " . $e->getMessage() . "');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .password-container {
            position: relative;
        }
        .password-container input {
            width: 100%;
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: gray;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Change Password</h2>
        <form action="Uchange_password.php" method="POST">
            <div class="mb-3 password-container">
                <label for="password" class="form-label">Enter new password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="bi bi-eye-slash toggle-password" onclick="togglePassword('password', this)"></i>
            </div>

            <div class="mb-3 password-container">
                <label for="cpassword" class="form-label">Confirm new password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword" required>
                <i class="bi bi-eye-slash toggle-password" onclick="togglePassword('cpassword', this)"></i>
            </div>

            <button type="submit" class="btn btn-primary w-100">Change Password</button>
        </form>
    </div>

    <script>
        function togglePassword(fieldId, icon) {
            let passwordField = document.getElementById(fieldId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                passwordField.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }
    </script>
</body>
</html>
