<?php
session_start();
require_once '../config/conn.php';

// check if otp and email are set in session
if (!isset($_SESSION['reset_otp']) || !isset($_SESSION['reset_email'])) {
    header("Location: forgot_password");
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
    $stored_otp = $_SESSION['reset_otp'];
    $otp_time = $_SESSION['otp_time'];

    // check OTP expiry (10 minutes)
    if (time() - $otp_time > 600) {
        $error = "OTP has expired. Please request a new one.";
        unset($_SESSION['reset_otp'], $_SESSION['otp_time']);
    }
    // verify OTP
    else if (password_verify($entered_otp, $stored_otp)) {

        $_SESSION['otp_verified'] = true;

        // OTP cannot be reused
        unset($_SESSION['reset_otp']);
        unset($_SESSION['otp_time']);

        header("Location: reset_password");
        exit();

    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | Pharmalyze</title>
    <link rel="stylesheet" href="../auth.css">
</head>

<body>
    <div class="container">
        <form method="POST" class="form">
            <h2>Verify OTP</h2>

            <?php if (isset($error)) { ?>
                <div class="error"> <?php echo $error; ?> </div>
            <?php } ?>

            <?php if (isset($status)) { ?>
                <div class="success"> <?php echo $status; ?> </div>
            <?php } ?>


            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <input type="text" name="otp" id="otp" maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                    autocomplete="one-time-code" required>
            </div>
            <input id="button" type="submit" value="Verify OTP" />
            <div class="link-text">
                <a href="forgot_password">Request New OTP</a>
            </div>
        </form>
    </div>
</body>

</html>