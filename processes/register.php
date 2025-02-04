<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup- Real Estate Platform </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../IMAGES/background.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            position: relative;
        }
        
        .background-blur {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: inherit;
            filter: blur(8px);
            z-index: -1;
        }
        .floating-table {
            background-color: white;
            color: black; font-weight: bold;
            border: 3px solid #000000; /* Reduced border size */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            width: 80%;
            max-width: 350px; /* Minimized form size */
        }
        .floating-table h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .form-label {
            font-size: 12px; font-weight: bold;
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
<div class="background-blur"></div>
<div class="floating-table">
        <h2 class="text-center">Sign Up</h2>
        <form id="registrationForm" action="register.php" method="POST">
            <div class="mb-2">
                <label for="id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id" name="id" required>
            </div>
            <div class="mb-2">
                <label for="fullname" class="form-label">Fullname</label>
                <input type="text" class="form-control" id="fullname" name="fullname" required>
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
