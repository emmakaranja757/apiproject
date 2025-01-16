<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger mt-3'>Invalid email format!</div>";
    } else {
        // Check if the email exists in the database
        $pdo = new PDO("mysql:host=localhost;dbname=users", "root", "");
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Email exists, generate unique code
            $code = rand(100000, 999999);  // A random 6-digit code
            // Save the code and timestamp in the database for 2FA verification
            $stmt = $pdo->prepare("UPDATE users SET reset_code = :code, code_timestamp = NOW() WHERE email = :email");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Send the code to the user's email
            mail($email, "Password Reset Code", "Your password reset code is: $code");

            echo "<div class='alert alert-success mt-3'>A password reset code has been sent to your email!</div>";
            // Redirect to the page where the user can enter the code
            header("Location: verify_code.php?email=" . urlencode($email));
            exit();
        } else {
            echo "<div class='alert alert-danger mt-3'>Email not found!</div>";
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
