<?php


function generate($length = 6) {
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= mt_rand(0, 9);
    }
    $_SESSION['otp'] = $otp; 
    return $otp;
}


$otp = generate();

?>