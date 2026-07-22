<?php
session_start();
require_once '../config/conn.php';

if (!$conn) {
    die("Database connection failed");
}


// Check OTP verification
if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {

        $email = $_SESSION['reset_email'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            session_unset();
            session_destroy();

            header("Location: sign_in.php");
            exit();
        } else {
            $error = "Error updating password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Pharmalyze</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <form method="POST" class="form">
        <h2>Reset Password</h2> <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div> <?php } ?>
        <div class="form-group"> <label for="password">New Password</label> <input type="password" name="password"
                id="password" required> </div>
        <div class="form-group"> <label for="confirm_password">Confirm Password</label> <input type="password"
                name="confirm_password" id="confirm_password" required> </div> <button type="submit">Reset
            Password</button>
    </form>
</body>

</html>