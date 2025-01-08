<?php
session_start(); // Start the session

$message = ''; // To hold the notification message
$isOtpValid = false; // To track OTP validity

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = htmlspecialchars(trim($_POST['otp']));

    // Retrieve OTP and email from session
    $sessionOtp = $_SESSION['otp'] ?? '';
    $email = $_SESSION['email'] ?? '';

    if (empty($email) || empty($sessionOtp)) {
        $message = "Session expired. Please register again.";
    } elseif ($enteredOtp === $sessionOtp) {
        $message = "OTP verified successfully! Welcome, $email.";
        $isOtpValid = true;

        // Optionally clear the session after successful verification
        unset($_SESSION['otp']);
        unset($_SESSION['email']);
    } else {
        $message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
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
    <script>
        // JavaScript to handle notification and redirection
        document.addEventListener('DOMContentLoaded', function () {
            <?php if (!empty($message)): ?>
                alert('<?php echo $message; ?>');
                <?php if ($isOtpValid): ?>
                    window.location.href = '../bs.html';
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
</head>
<body>
    <div class="floating-table">
        <h2 class="text-center">Verify OTP</h2>
        <form id="verifyOtpForm" action="" method="POST">
            <div class="mb-2">
                <label for="otp" class="form-label">Enter OTP</label>
                <input type="text" class="form-control" id="otp" name="otp" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Verify</button>
        </form>
    </div>
</body>
</html>