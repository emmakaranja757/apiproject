<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $new_password = htmlspecialchars(trim($_POST['password']));

    // Validate password strength (e.g., minimum 8 characters)
    if (strlen($new_password) < 8) {
        echo "<div class='alert alert-danger mt-3'>Password must be at least 8 characters long!</div>";
    } else {
        // Hash the new password before storing it
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the user's password in the database
        $pdo = new PDO("mysql:host=localhost;dbname=users", "root", "425096");
        $stmt = $pdo->prepare("UPDATE info SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Optionally, you can clear the reset code after successful password change
        $stmt = $pdo->prepare("UPDATE info SET reset_code = NULL, code_timestamp = NULL WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        echo "<div class='alert alert-success mt-3'>Your password has been successfully updated!</div>";
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
</head>
<body>
    <div class="container mt-5">
        <h2>Change Your Password</h2>
        <form action="change_password.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Your email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>" required readonly>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Enter new password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</body>
</html>
