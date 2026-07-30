<?php
$title = "Pharmacist Dashboard";
include 'partials/header.php';
include '../../config/conn.php';
?>
<link rel="stylesheet" href="setting.css">


<?php
// Logged in user's ID
$id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($conn, $query);

$user = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Settings</title>

    <link rel="stylesheet" href="settings.css">

    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="settings-container">

    <div class="settings-card">

        <div class="profile">

            <img src="uploads/<?= $user['profile_image']; ?>">

            <h2><?= $user['name']; ?></h2>

            <p>Pharmacist</p>

        </div>

        <div class="info">

            <div class="row">
                <span>Full Name</span>
                <p><?= $user['name']; ?></p>
            </div>

            <div class="row">
                <span>Email</span>
                <p><?= $user['email']; ?></p>
            </div>

            <div class="row">
                <span>Phone</span>
                <p><?= htmlspecialchars($user['phone'] ?? '') ?></p>
            </div>

            <div class="row">
                <span>Pharmacy Name</span>
                <p><?= htmlspecialchars($user['pharmacy_name'] ?? '') ?></p>
            </div>

            <div class="row">
                <span>License Number</span>
                <p><?= htmlspecialchars($user['license_number'] ?? '') ?></p>
            </div>

            <div class="row">
                <span>Address</span>
                <p><?= htmlspecialchars($user['address'] ?? '') ?></p>
            </div>

        </div>

        <div class="buttons">

            <a href="edit_profile.php" class="edit-btn">

                <i class="fa-solid fa-pen"></i>

                Update Profile

            </a>

            <a href="change_password.php" class="password-btn">

                <i class="fa-solid fa-lock"></i>

                Change Password

            </a>

        </div>

    </div>

</div>
<?php
include 'partials/footer.php';
?> 