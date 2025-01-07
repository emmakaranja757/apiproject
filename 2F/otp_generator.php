<?php


function generate($length = 6) {
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= mt_rand(0, 9);
    }
    $_SESSION['otp'] = $otp; // Store OTP in session
    return $otp;
}

// Generate the OTP and store it in the session
$otp = generate();
//echo "Generated OTP: $otp"; // For debugging (optional)
?>