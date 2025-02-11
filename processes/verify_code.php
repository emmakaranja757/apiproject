<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $entered_code = htmlspecialchars(trim($_POST['code']));

    // Retrieve the reset code from the database for the provided email
    $pdo = new PDO("mysql:host=localhost;dbname=users", "root", "425096");
    $stmt = $pdo->prepare("SELECT reset_code, code_timestamp FROM info WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && $user['reset_code'] == $entered_code) {
        // Verify if the code has expired (e.g., valid for 15 minutes)
        $timestamp = strtotime($user['code_timestamp']);
        $current_time = time();

        if (($current_time - $timestamp) <= 900) { // 15 minutes
            // Code is valid and hasn't expired
            echo "<div class='alert alert-success mt-3'>Code verified successfully!</div>";
            // Redirect to change password page
            header("Location: change_password.php?email=" . urlencode($email));
            exit();
        } else {
            echo "<div class='alert alert-danger mt-3'>The code has expired!</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-3'>Invalid code!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Enter the Reset Code</h2>
        <form action="verify_code.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Your email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>" required readonly>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Enter the reset code</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify Code</button>
        </form>
    </div>
</body>
</html>
