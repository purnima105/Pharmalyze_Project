<?php
session_start();
require_once '../config/conn.php';
if (!isset($conn)) {
    die('$conn is not defined');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $otp = rand(100000, 999999);
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_email'] = $email;
        $_SESSION['otp_time'] = time();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUsername;
            $mail->Password = $smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $smtpPort;

            $mail->setFrom($smtpFromEmail, $smtpFromName);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Password Reset OTP";
            $mail->Body = "
                <p>Your OTP for password reset is:</p>
                <h2>$otp</h2>
                <p>This OTP will expire in <b>10 minutes</b>.</p>
            ";

            $mail->send();

            $_SESSION['status'] = "An OTP has been sent to your email. Please check your inbox (and spam).";
            header("Location: verify-otp.php");
            exit();

        } catch (Exception $e) {
            $error = "Mailer Error: " . $mail->ErrorInfo;
        }

    } else {
        $error = "Email address not found.";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forgot password</title>
    <link rel="stylesheet" href="../auth.css">
</head>

<body>
    <form class="form" method="POST" action="">
        <h1>Forgot Password</h1>
        <?php
         if (isset($error)) { 
        ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
        <?php 
        } 
        ?>
        <div class="form-group">
            <label for="email">Email <small>*</small> </label>
            <input type="email" id="email" name="email">
        </div>
        <div class="link-text">
            <a href="login.php">Back to Login</a>
        </div>
        <button id="button" type="submit">Send OTP</button>
    </form>
</body>

</html>