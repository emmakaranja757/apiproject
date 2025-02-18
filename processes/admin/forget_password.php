<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger mt-3'>Invalid email format!</div>";
    } else {
        try {
            include('../../2F/otp_generator.php');
            include('../../PHPMailer/mailer_demo.php');
            include('../../Dbconn/db_connection.php');

            $pdo = getDatabaseConnection();

            // Fetch user full name from database using email
            $stmt = $pdo->prepare("SELECT fullname FROM info WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $name = $user['fullname']; // Assign the full name
            } else {
                echo "<script>alert('Email not found!');</script>";
                exit();
            }

            $otp = generate();

            // **Store OTP & email in session**
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            // **Debugging output (Remove in production)**
            error_log("DEBUG: OTP stored in session: " . $_SESSION['otp']);
            error_log("DEBUG: Email stored in session: " . $_SESSION['email']);

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
                       window.location.href='verify_code.php';
                      </script>";
            } else {
                echo "<script>alert('Email sending failed. Please check your SMTP settings.');</script>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger mt-3'>Database Error: " . $e->getMessage() . "</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger mt-3'>Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            background: white;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 800px;
            border: 2px solid #ccc;
        }
        .illustration-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
            padding: 20px;
        }
        .illustration {
            width: 100%;
            max-width: 300px;
        }
        .form-container {
            flex: 1;
            padding: 30px;
            text-align: center;
        }
        .btn-primary {
            width: 100%;
            border-radius: 25px;
        }
        .form-control {
            border-radius: 25px;
            padding: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="illustration-container">
            <img src="../../IMAGES/forgotpassword.jpeg" alt="Forgot Password Illustration" class="illustration">
        </div>
        <div class="form-container">
            <h3 class="mb-3">Forgot Password?</h3>
            <p class="text-muted">Enter your email address </p>
            <form action="forgot_password.php" method="POST">
                <div class="mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" required>
                </div>
                <button type="submit" class="btn btn-primary">Next</button>
            </form>
        </div>
    </div>
</body>
</html>
