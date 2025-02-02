<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: rgba(112, 41, 99, 0.2); /* Faint background color */
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
        }
        .floating-table {
            background-color: white;
            color: black;
            border: 3px solid #702963; /* Reduced border size */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            width: 80%;
            max-width: 350px; /* Minimized form size */
        }
        .floating-table h2 {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .form-label {
            font-size: 12px;
        }
        .form-control {
            height: 28px;
            font-size: 12px;
        }
        button {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="floating-table">
        <h2 class="text-center">Register</h2>
        <form id="registrationForm" action="register.php" method="POST">
            <div class="mb-2">
                <label for="id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id" name="id" required>
            </div>
            <div class="mb-2">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">Show</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <button type="reset" class="btn btn-secondary w-100 mt-2">Reset</button>
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
