<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Real Estate</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Background Image with Blur */
        body {
            background: url('../IMAGES/background.jpeg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: inherit;
            filter: blur(8px); /* Slight blur effect */
            z-index: -1;
        }

        /* Login Form Styling */
        .login-container {
            max-width: 400px;
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            border: 4px solid #000000; 
        }

        /* Centering */
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Slightly Bold Text */
        .login-container h2 {
            font-weight: 600;
        }

        .login-container label {
            font-weight: 500;
        }

        /* Button Styling */
        .btn-primary {
            width: 100%;
            font-weight: 600;
        }

        /* Forgot Password Link */
        .forgot-password {
            display: block;
            text-align: right;
            margin-top: 5px;
            font-size: 14px;
        }

    </style>
</head>
<body>

    <!-- Login Form -->
    <div class="login-wrapper">
        <div class="login-container">
            <h2 class="text-center mb-4">Login</h2>
            <form action="login_process.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <p class="mt-3 text-center">
                Don't have an account? <a href="signup.php">Sign up</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
