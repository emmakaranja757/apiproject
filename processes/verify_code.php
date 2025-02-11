<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']??''));
    $entered_code = htmlspecialchars(trim($_POST['code']?? ''));

    echo "DEBUG: Entered Code: " . htmlspecialchars($entered_code) . "<br>";


    // Retrieve the reset code from the database for the provided email
    $pdo = new PDO("mysql:host=localhost;dbname=users", "root", "425096");
    $stmt = $pdo->prepare("SELECT reset_code, code_timestamp FROM info WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    /*if ($user) {
        echo "DEBUG: Fetched Stored Code: " . htmlspecialchars($user['reset_code']) . "<br>";
    } else {
        echo "DEBUG: No user found for this email!<br>";
    }
        echo "DEBUG: Entered Code: '" . htmlspecialchars($entered_code) . "<br>";
        echo "Code Match: " . (trim($user['reset_code'])== $entered_code ? "YES" : "NO") . "<br>";
        exit();
    }*/
    
    if ($user && trim($user['reset_code']) ===trim($entered_code)) { 

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .illustration {
            width: 120px;
            margin-bottom: 15px;
        }
        .code-input {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        .code-input input {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border: 1px solid #ced4da;
            border-radius: 10px;
            outline: none;
        }
        .resend-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
        }
        .resend-btn {
            color: red;
            cursor: pointer;
            margin-right: 10px;
        }
        .resend-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        #countdown {
            color: gray;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../IMAGES/envelope.jpeg" alt="Verification Illustration" class="illustration">
        <h3>Verify Code</h3>
         <!-- Show error message at the top -->
         <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger' style='text-align: center;'>
                    " . $_SESSION['error'] . "
                  </div>";
            unset($_SESSION['error']); // Clear error after displaying
        }
        ?>
        <p>We have sent a verification code to your email. Please enter the code below.</p>
        
        <form action="verify_code.php" method="POST" onsubmit="combineCode()">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
            <input type="hidden" name="code" id="fullCode">

            <div class="code-input">
                <input type="text" maxlength="1"  required oninput="moveToNext(this, 0)">
                <input type="text" maxlength="1"  required oninput="moveToNext(this, 1)">
                <input type="text" maxlength="1"  required oninput="moveToNext(this, 2)">
                <input type="text" maxlength="1"  required oninput="moveToNext(this, 3)">
                <input type="text" maxlength="1"  required oninput="moveToNext(this, 4)">
                <input type="text" maxlength="1"  required oninput="moveToNext(this, 5)">
            </div>
            <button type="submit" class="btn btn-primary w-100">Verify Code</button>
        </form>
     <script>
    function combineCode() {
        let inputs = document.querySelectorAll(".code-input input");
        let fullCode = "";
        inputs.forEach(input => {
            fullCode += input.value;
        });
        document.getElementById("fullCode").value = fullCode;
    }
    function moveToNext(input, index) {
        let inputs = document.querySelectorAll('.code-input input');
        if (input.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    }
      </script>

        <!-- Resend Code + Countdown Timer -->
        <div class="resend-container">
            <div id="resend" class="resend-btn disabled" onclick="resendCode()">
                Resend Code
            </div>
            <span id="countdown">(180s)</span>
        </div>
    </div>

    <script>
        let timeLeft = 180;
        let countdownElement = document.getElementById("countdown");
        let resendButton = document.getElementById("resend");

        function startCountdown() {
            let timer = setInterval(() => {
                if (timeLeft > 0) {
                    countdownElement.innerText = `(${timeLeft}s)`;
                    timeLeft--;
                } else {
                    clearInterval(timer);
                    countdownElement.innerText = "Code expired";
                    resendButton.classList.remove("disabled"); // Enable resend button
                }
            }, 1000);
        }

        function moveToNext(input, index) {
            const inputs = document.querySelectorAll('.code-input input');
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        }

        function resendCode() {
            if (resendButton.classList.contains("disabled")) return;

            let email = "<?php echo htmlspecialchars($_GET['email']); ?>";

            fetch("forgot_password.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "email=" + encodeURIComponent(email)
            }).then(response => response.text()).then(data => {
                alert("A new code has been sent to your email.");
                
                // Restart the countdown
                resendButton.classList.add("disabled");
                timeLeft = 60;
                countdownElement.innerText = `(60s)`;
                startCountdown();
            }).catch(error => console.error('Error:', error));
        }

        startCountdown(); // Start countdown when page loads
    </script>
</body>
</html>
