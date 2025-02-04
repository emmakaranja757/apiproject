<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background Blur Effect */
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
            background: rgba(0, 0, 0, 0.4); /* Dark overlay */
            backdrop-filter: blur(8px); /* Blurred effect */
            z-index: -1;
        }
        
        /* Login Container */
        .login-container {
            width: 400px;
            margin: 100px auto;
            padding: 25px;
            border: 2px solid #000;
            background: rgba(255, 255, 255, 0.85); /* Slightly transparent */
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            font-weight: none;
        }

        .form-control {
            font-weight: 500; /* Slightly bold */
        }

        .btn-primary {
            font-weight: none;
        }

        /* Forgot Password */
        .forgot-password {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            font-weight: none;
            color: #007bff;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="admin_auth.php" method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
        </form>
    </div>
</body>
</html>
