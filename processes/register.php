<?php
session_start(); // Start the session

$message = ''; // To hold the notification message
$otp = ''; // To store the generated OTP

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../db_connection.php';
    include '../2F/otp_generator.php'; // Include the OTP generator file
    include '../PHPMailer/mailer_demo.php'; // Include the mailer file

    $id = htmlspecialchars(trim($_POST['id']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    try {
        $pdo = getDbConnection();

        // Check if the ID already exists
        $checkQuery = "SELECT id FROM info WHERE id = :id";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':id', $id);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            // ID already exists
            $message = "<script>alert('The ID already exists in the database. Please use a different ID.');</script>";
        } else {
            // Proceed with the insertion
            $query = "INSERT INTO info (id, username, email, password) VALUES (:id, :username, :email, :password)";
            $stmt = $pdo->prepare($query);

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                // Generate OTP after successful registration
                $otp = generate(); // Call the generate function from otp_generator.php

                // Store email and OTP in the session
                $_SESSION['email'] = $email;
                $_SESSION['otp'] = $otp;

                // Send email with OTP
                if (sendMail($email, $otp)) {
                    // Redirect to register_user.php after successful email
                    header("Location: register_user.php");
                    exit(); // Ensure no further code is executed
                } else {
                    $message = "<div class='alert alert-danger mt-3'>Failed to send OTP. Please try again later.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger mt-3'>Failed to register user. Please try again!</div>";
            }
        }
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger mt-3'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #702963;
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            font-size: 14px;
        }
        .floating-table {
            background-color: white;
            color: black;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            width: 90%;
            max-width: 500px;
        }
        .floating-table h2 {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .form-label {
            font-size: 13px;
        }
        .form-control {
            height: 32px;
            font-size: 13px;
        }
        button {
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="floating-table">
        <h2 class="text-center">User Registration Form</h2>
        <!-- Notification Message -->
        <?php if (!empty($message)) echo $message; ?>

        <form id="registrationForm" action="register.php" method="POST">
            <div class="mb-2">
                <label for="id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id" name="id" required>
            </div>
            <div class="mb-2">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">Show</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <button type="reset" class="btn btn-secondary w-100 mt-2">Reset</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }

        document.getElementById('registrationForm').addEventListener('submit', function (e) {
            const id = document.getElementById('id').value.trim();
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            // ID Validation
            if (!id) {
                alert('ID is required.');
                e.preventDefault();
                return;
            }

            // Username Validation
            if (username.length < 3) {
                alert('Username must be at least 3 characters long.');
                e.preventDefault();
                return;
            }

            // Email Validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Invalid email format.');
                e.preventDefault();
                return;
            }

            // Password Validation
           // const passwordRegex = /^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[@$!%?&])[A-Za-z\d@$!%?&]{8,}$/;
           // if (!passwordRegex.test(password)) {
             //   alert('Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
              //  e.preventDefault();
               // return;
           // }
        });
    </script>
</body>
</html>