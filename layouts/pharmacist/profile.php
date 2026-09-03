<?php
session_start();
require_once __DIR__ . "/../../config/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

//  Get User Information
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Get Pharmacy Information
// Get Pharmacy Information
$pharmacy = null;

$stmt = mysqli_prepare(
    $conn,
    "SELECT 
        p.id,
        p.name,
        p.registration_no,
        p.email,
        p.phone,
        p.pan_no,
        p.province,
        p.district,
        p.municipality,
        p.ward,
        p.street,
        p.logo,
        p.status
     FROM pharmacies p
     WHERE p.user_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$pharmacyResult = mysqli_stmt_get_result($stmt);

if ($pharmacyResult) {
    $pharmacy = mysqli_fetch_assoc($pharmacyResult);
}

mysqli_stmt_close($stmt);

?>

<?php

$title = "My Profile";

include 'partials/header.php';

?>

<link rel="stylesheet" href="../../auth/profile.css">


<div class="profile-container">

    <div class="profile-hero"> 

        <div class="profile-avatar">

            <img
                src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=00B894&color=fff&size=160"
                alt="Profile"
            >
        </div>


        <div class="profile-main-info">

            <h1>
                <?= htmlspecialchars($user['name'] ?? 'User') ?>
            </h1>

            <p class="profile-role">
                <i class="fa-solid fa-user-shield"></i>

                <?= htmlspecialchars(ucfirst($user['role'] ?? 'Pharmacy User')) ?>
            </p>

            <div class="profile-meta">

                <span>
                    <i class="fa-solid fa-envelope"></i>
                    <?= htmlspecialchars($user['email'] ?? 'No email') ?>
                </span>

                <span>
                    <i class="fa-solid fa-phone"></i>
                    <?= htmlspecialchars($user['phone'] ?? 'No phone') ?>
                </span>

            </div>

        </div>


        <div class="profile-actions">

            <span class="status-badge">
                <span></span>
                <?= htmlspecialchars(ucfirst($user['status'] ?? 'Active')) ?>
            </span>

            <a href="update_profile" class="edit-profile-btn">
                <i class="fa-solid fa-pen"></i>
                Update Profile
            </a>

        </div>

    </div>

    <!-- personal information -->

    <section class="profile-section">

        <div class="section-heading">

            <div class="section-icon">
                <i class="fa-solid fa-user"></i>
            </div>

            <div>
                <h2>Personal Information</h2>
                <p>Your personal account information</p>
            </div>

        </div>


        <div class="info-grid">

            <div class="info-item">

                <span class="info-label">
                    Full Name
                </span>

                <strong>
                    <?= htmlspecialchars($user['name'] ?? 'Not provided') ?>
                </strong>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Email Address
                </span>

                <strong>
                    <?= htmlspecialchars($user['email'] ?? 'Not provided') ?>
                </strong>

            </div>


            <div class="info-item">

                <span class="info-label">
                    Phone Number
                </span>

                <strong>
                    <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?>
                </strong>
            </div>

            <div class="info-item">
                <span class="info-label">
                    Account Role
                </span>
                <strong>
                    <?= htmlspecialchars(ucfirst($user['role'] ?? 'User')) ?>
                </strong>
            </div>

            <div class="info-item full-width">
                <span class="info-label">
                    Address
                </span>
                <strong>
                    <?= htmlspecialchars($user['address'] ?? 'Not provided') ?>
                </strong>
            </div>
        </div>
    </section>

 <!-- PHARMACY INFORMATION -->

    <section class="profile-section pharmacy-section">

        <div class="section-heading">

            <div class="section-icon pharmacy-icon">
                <i class="fa-solid fa-prescription-bottle-medical"></i>
            </div>

            <div>
                <h2>Pharmacy Information</h2>
                <p>Registered pharmacy details</p>
            </div>

        </div>


        <?php if ($pharmacy): ?>

            <div class="pharmacy-card">
                <div class="pharmacy-header">
                    <div class="pharmacy-logo">
                        <?php if (!empty($pharmacy['logo'])): ?>
                            <img
                                src="../../uploads/pharmacy/<?= htmlspecialchars($pharmacy['logo']) ?>"
                                alt="Pharmacy Logo"
                            >
                        <?php else: ?>
                            <i class="fa-solid fa-prescription-bottle-medical"></i>
                        <?php endif; ?>

                    </div>


                    <div>

                        <h3>
                            <?= htmlspecialchars($pharmacy['name'] ?? 'Pharmacy') ?>
                        </h3>

                        <p>
                            <i class="fa-solid fa-location-dot"></i>

                            <?= htmlspecialchars($pharmacy['district'] ?? '') ?>,
                            <?= htmlspecialchars($pharmacy['province'] ?? '') ?>
                        </p>

                    </div>

                </div>


                <div class="pharmacy-details">

                    <div class="pharmacy-detail">

                        <span>
                            Registration Number
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pharmacy['registration_no'] ?? 'Not provided'
                            ) ?>
                        </strong>

                    </div>


                    <div class="pharmacy-detail">

                        <span>
                            PAN Number
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pharmacy['pan_no'] ?? 'Not provided'
                            ) ?>
                        </strong>

                    </div>


                    <div class="pharmacy-detail">

                        <span>
                            Email
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pharmacy['email'] ?? 'Not provided'
                            ) ?>
                        </strong>

                    </div>


                    <div class="pharmacy-detail">

                        <span>
                            Phone
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pharmacy['phone'] ?? 'Not provided'
                            ) ?>
                        </strong>

                    </div>


                    <div class="pharmacy-detail">

                        <span>
                            Municipality
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pharmacy['municipality'] ?? 'Not provided'
                            ) ?>
                        </strong>

                    </div>


                    <div class="pharmacy-detail">

                        <span>
                            Ward
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pharmacy['ward'] ?? 'Not provided'
                            ) ?>
                        </strong>

                    </div>


                    <div class="pharmacy-detail full-width">

                        <span>
                            Street Address
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pharmacy['street'] ?? 'Not provided'
                            ) ?>
                        </strong>

                    </div>

                </div>

            </div>

        <?php else: ?>

            <div class="no-pharmacy">

                <i class="fa-solid fa-store-slash"></i>

                <h3>No Pharmacy Information</h3>

                <p>
                    Pharmacy details have not been registered with this account yet.
                </p>

            </div>

        <?php endif; ?>

    </section>
</div>


<?php

include 'partials/footer.php';

?>