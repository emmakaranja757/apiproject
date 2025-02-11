<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $new_password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['cpassword']));

    // Validate password strength (e.g., minimum 8 characters)
    if (strlen($new_password) < 8) {
        echo "<div class='alert alert-danger text-center'>Password must be at least 8 characters long!</div>";
    } elseif ($new_password !== $confirm_password) {
        echo "<div class='alert alert-danger text-center'>Passwords do not match!</div>";
    } else {
        // Hash the new password before storing it
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the user's password in the database
        $pdo = new PDO("mysql:host=localhost;dbname=users", "root", "425096");
        $stmt = $pdo->prepare("UPDATE info SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Clear the reset code after successful password change
        $stmt = $pdo->prepare("UPDATE info SET reset_code = NULL, code_timestamp = NULL WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        echo "<div class='alert alert-success text-center'>Your password has been successfully updated!</div>";
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
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Change Your Password</h2>
        <form action="change_password.php" method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">

            <div class="mb-3">
                <label for="password" class="form-label">Enter new password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm new password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Change Password</button>
        </form>
    </div>
</body>
</html>
