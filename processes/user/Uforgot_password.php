<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger mt-3'>Invalid email format!</div>";
    } else {
        try {
            // Database connection with error handling
            $pdo = new PDO("mysql:host=localhost;dbname=users", "root", "425096", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // Check if email exists
            $stmt = $pdo->prepare("SELECT * FROM info WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Email exists, generate a 6-digit reset code
                $reset_code = rand(100000, 999999);

                // Save code in the database
                $stmt = $pdo->prepare("UPDATE info SET reset_code = :reset_code, code_timestamp = NOW() WHERE email = :email");
                $stmt->bindParam(':reset_code', $reset_code);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                //DEBUG: Print the generated code
                echo "DEBUG: New Generated Code: ";

                $mail = require __DIR__ . '/mailer_demo.php';
                // Send reset code to user's email
                $mail -> setFrom("sawabu@gmail.com","Sawabu Ltd");
                $mail-> addAddress($email);
                $mail->Subject = "Password Reset Code";
                $mail->Body = "Your password reset code is: $reset_code";
                $mail->Headers = "From: noreply@yourdomain.com\r\n"; // Change the email to a valid one
               try{
                $mail->send();
               }catch(Exception){
                echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
               }

                if ($mail) {
                    // Redirect before any HTML output
                    header("Location: verify_code.php?email=" . urlencode($email));
                    exit();
                } else {
                    echo "<div class='alert alert-danger mt-3'>Failed to send email. Please try again later.</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-3'>Email not found!</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger mt-3'>Database Error: " . $e->getMessage() . "</div>";
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
            <img src="../IMAGES/forgotpassword.jpeg" alt="Forgot Password Illustration" class="illustration">
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
