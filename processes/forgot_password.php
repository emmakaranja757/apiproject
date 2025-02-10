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
                $code = rand(100000, 999999);

                // Save code in the database
                $stmt = $pdo->prepare("UPDATE info SET reset_code = :code, code_timestamp = NOW() WHERE email = :email");
                $stmt->bindParam(':code', $code);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                // Send reset code to user's email
                $subject = "Password Reset Code";
                $message = "Your password reset code is: $code";
                $headers = "From: noreply@yourdomain.com\r\n"; // Change the email to a valid one

                if (mail($email, $subject, $message, $headers)) {
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
</head>
<body>
    <div class="container mt-5">
        <h2>Forgot Password</h2>
        <form action="forgot_password.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Enter your email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Code</button>
        </form>
    </div>
</body>
</html>
