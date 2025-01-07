<?php
$message = ''; // To hold the notification message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connection.php';

    $id = htmlspecialchars(trim($_POST['id']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger mt-3'>Invalid email format!</div>";
    } elseif (strlen($password) < 8) {
        $message = "<div class='alert alert-danger mt-3'>Password must be at least 8 characters long!</div>";
    } else {
        try {
            $pdo = getDbConnection();

            $query = "INSERT INTO info (id, username, email, password) VALUES (:id, :username, :email, :password)";
            $stmt = $pdo->prepare($query);

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success mt-3'>User registered successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger mt-3'>Failed to register user. Please try again!</div>";
            }
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger mt-3'>Error: " . $e->getMessage() . "</div>";
        }
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
</head>
<body>
    <div class="container mt-5">
        <h2>User Registration Form</h2>
        <!-- Notification Message -->
        <?php if (!empty($message)) echo $message; ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id" name="id" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">Show</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
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