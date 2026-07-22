<?php 
session_start();
require_once '../config/conn.php';

// check if otp and email are set in session
if (!isset($_SESSION['reset_otp']) || !isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

// capture status message
$status = null;
if (isset($_SESSION['status'])) {
    $status = $_SESSION['status'];
    unset($_SESSION['status']);
}

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $entered_otp = $_POST['otp'];
    $stored_otp  = $_SESSION['reset_otp'];
    $otp_time    = $_SESSION['otp_time'];

    // check OTP expiry (10 minutes)
    if (time() - $otp_time > 600) {
        $error = "OTP has expired. Please request a new one.";
        unset($_SESSION['reset_otp'], $_SESSION['otp_time']);
    }
    // verify OTP
    else if ($entered_otp == $stored_otp) {

        $_SESSION['otp_verified'] = true; 
        header("Location: reset_password.php");
        exit();
    }
    else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="container">
        <form method="POST" class="form">
            <h2>verify otp</h2>

            <?php if(isset($error)){ ?>
                <div class="error"> <?php echo $error; ?> </div>
            <?php } ?>

            <?php if(isset($status)){ ?>
                <div class="success"> <?php echo $status; ?> </div>
            <?php } ?>


            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <input type="number" name="otp" id="otp" required>
            </div>
            <button type="submit">verify otp</button>
            <div class="links">
                <a href="forgot_password.php">request new otp</a>
            </div>
        </form>
    </div>
</body>
</html>