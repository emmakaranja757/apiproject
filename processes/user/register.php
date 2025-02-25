<?php
session_start();
require '../../Dbconn/db_connection.php'; // Ensure this file contains the function getDatabaseConnection()

$pdo = getDatabaseConnection(); // Call the function to get the PDO connection

if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Secure password hashing

    try {
        // **Check if email already exists**
        $checkSql = "SELECT COUNT(*) FROM info WHERE Email = :email";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $checkStmt->execute();
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
            echo "<script>alert('Email is already registered. Please use another email.'); window.location.href='register.php';</script>";
            exit();
        }

        // **Insert new user**
        $sql = "INSERT INTO info (fullname, Email, `Password`) VALUES (:fullname, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "<script>alert('Registration failed. Please try again.');</script>";
            exit();
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database Error: " . $e->getMessage() . "');</script>";
        exit();
    }

    include('../../2F/otp_generator.php');
    include('../../PHPMailer/mailer_demo.php');

    $otp = generate();
    
    // **Store OTP & email in session**
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // **Debugging output (Remove in production)**
    error_log("DEBUG: OTP stored in session: " . $_SESSION['otp']);
    error_log("DEBUG: Email stored in session: " . $_SESSION['email']);

    $name = $fullname;
    $Subject = "Registration";
    $Body = "<html>
                <body>
                    <div><strong>Hello, $name</strong></div><br><br>
                    <div style='padding-top:8px;'>
                    You have successfully registered. To verify your account, 
                    use the OTP below and enter it on the verification page. 
                    <br><br>Your OTP: <strong>$otp</strong><br>
                    </div>
                </body>
            </html>";

    if (sendMail($email, $Subject, $Body, $name)) {
        echo "<script>
               alert('Email sent successfully. Please check your inbox.');
               window.location.href='Uverify_code.php';
              </script>";
    } else {
        echo "<script>alert('Email sending failed. Please check your SMTP settings.');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup- Real Estate Platform </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('/apiproject/IMAGES/background.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            position: relative;
        }
        
        .background-blur {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: inherit;
            filter: blur(8px);
            z-index: -1;
        }
        .floating-table {
            background-color: white;
            color: black; font-weight: bold;
            border: 3px solid #000000; /* Reduced border size */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            width: 80%;
            max-width: 350px; /* Minimized form size */
        }
        .floating-table h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .form-label {
            font-size: 12px; font-weight: bold;
        }
        .form-control {
            height: 28px;
            font-size: 12px;
        }
        button {
            font-size: 12px;
        }
    </style>
</head>
<div class="background-blur"></div>
<div class="floating-table">
        <h2 class="text-center">Sign Up</h2>
        <form id="registrationForm" action="register.php" method="POST">
            <div class="mb-2">
                <label for="fullname" class="form-label">Fullname</label>
                <input type="text" class="form-control" id="fullname" name="fullname" required>
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
            <button type="submit" class="btn btn-primary w-100" name = "register">Register</button>
            <button type="reset" class="btn btn-secondary w-100 mt-2">Reset</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
