<?php

session_start();

require_once __DIR__ . "/../../config/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$success = "";
$error = "";

$sql = "
    SELECT
    u.id AS user_id,
    u.name AS user_name,
    u.email AS user_email,
    u.phone AS user_phone,

    p.id AS pharmacy_id,
    p.name AS pharmacy_name,
    p.registration_no,
    p.email AS pharmacy_email,
    p.phone AS pharmacy_phone,
    p.pan_no,
    p.province,
    p.district,
    p.municipality,
    p.ward

    FROM users u

    LEFT JOIN pharmacies p
        ON p.user_id = u.id

    WHERE u.id = ?

    LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    die("User information not found.");
}


/* =========================================================
   UPDATE INFORMATION
========================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ---------------- USER INFORMATION ---------------- */

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $user_phone = trim($_POST['user_phone'] ?? '');


    /* ---------------- PHARMACY INFORMATION ---------------- */

    $pharmacy_name = trim($_POST['pharmacy_name'] ?? '');
    $registration_no = trim($_POST['registration_no'] ?? '');
    $pharmacy_email = trim($_POST['pharmacy_email'] ?? '');
    $pharmacy_phone = trim($_POST['pharmacy_phone'] ?? '');
    $pan_no = trim($_POST['pan_no'] ?? '');

    $province = trim($_POST['province'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $municipality = trim($_POST['municipality'] ?? '');
    $ward = trim($_POST['ward'] ?? '');


    /* =====================================================
       VALIDATION
    ===================================================== */

    if ($name === '' || $email === '') {

        $error = "Name and email are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } elseif (
        $pharmacy_email !== '' &&
        !filter_var($pharmacy_email, FILTER_VALIDATE_EMAIL)
    ) {

        $error = "Please enter a valid pharmacy email address.";

    } elseif ($ward !== '' && (!is_numeric($ward) || $ward < 1)) {

        $error = "Please enter a valid ward number.";

    }


    /* =====================================================
       CHECK DUPLICATE USER EMAIL
    ===================================================== */

    if ($error === '') {

        $stmt = mysqli_prepare($conn, "
            SELECT id
            FROM users
            WHERE email = ?
            AND id != ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $email,
            $user_id
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "This email is already being used.";
        }

        mysqli_stmt_close($stmt);
    }


    /* =====================================================
       CHECK PHARMACY UNIQUE FIELDS
    ===================================================== */

    if ($error === '' && !empty($data['pharmacy_id'])) {

        $pharmacy_id = $data['pharmacy_id'];

        // Registration number
        if ($registration_no !== '') {

            $stmt = mysqli_prepare($conn, "
                SELECT id
                FROM pharmacies
                WHERE registration_no = ?
                AND id != ?
                LIMIT 1
            ");

            mysqli_stmt_bind_param(
                $stmt,
                "si",
                $registration_no,
                $pharmacy_id
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $error = "This registration number is already registered.";
            }

            mysqli_stmt_close($stmt);
        }
    }


    /* =====================================================
       UPDATE DATABASE
    ===================================================== */

    if ($error === '') {

        mysqli_begin_transaction($conn);

        try {

            /* ---------------------------------------------
               UPDATE USERS
            --------------------------------------------- */

            $stmt = mysqli_prepare($conn, "
                UPDATE users
                SET
                    name = ?,
                    email = ?,
                    phone = ?
                WHERE id = ?
            ");

            mysqli_stmt_bind_param(
                $stmt,
                "sssi",
                $name,
                $email,
                $user_phone,
                $user_id
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Unable to update user information.");
            }

            mysqli_stmt_close($stmt);


            /* ---------------------------------------------
               UPDATE PHARMACY
            --------------------------------------------- */

            if (!empty($data['pharmacy_id'])) {

                $pharmacy_id = $data['pharmacy_id'];

                $stmt = mysqli_prepare($conn, "
                    UPDATE pharmacies
                    SET
                        name = ?,
                        registration_no = ?,
                        email = ?,
                        phone = ?,
                        pan_no = ?,
                        province = ?,
                        district = ?,
                        municipality = ?,
                        ward = ?
                    WHERE id = ?
                ");

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssssii",
                    $pharmacy_name,
                    $registration_no,
                    $pharmacy_email,
                    $pharmacy_phone,
                    $pan_no,
                    $province,
                    $district,
                    $municipality,
                    $ward,
                    $pharmacy_id
                );

                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Unable to update pharmacy information.");
                }

                mysqli_stmt_close($stmt);
            }


            /* ---------------------------------------------
               COMMIT
            --------------------------------------------- */

            mysqli_commit($conn);

            $success = "Profile information updated successfully.";

            /* Update displayed data */
            $data['user_name'] = $name;
            $data['user_email'] = $email;
            $data['user_phone'] = $user_phone;

            $data['pharmacy_name'] = $pharmacy_name;
            $data['registration_no'] = $registration_no;
            $data['pharmacy_email'] = $pharmacy_email;
            $data['pharmacy_phone'] = $pharmacy_phone;
            $data['pan_no'] = $pan_no;
            $data['province'] = $province;
            $data['district'] = $district;
            $data['municipality'] = $municipality;
            $data['ward'] = $ward;

            $_SESSION['name'] = $name;

        } catch (Exception $e) {

            mysqli_rollback($conn);

            $error = $e->getMessage();
        }
    }
}

?>

<?php

$title = "Update Profile";

include 'partials/header.php';

?>

<link rel="stylesheet" href="../../css/update_profile.css">


<div class="dashboard-content">

    <div class="profile-page">

        <!-- =================================================
             PAGE HEADER
        ================================================== -->

        <div class="profile-header">

            <h1>Update Profile</h1>

            <p>
                Manage your personal and pharmacy information.
            </p>

        </div>


        <!-- =================================================
             SUCCESS / ERROR
        ================================================== -->

        <?php if ($success): ?>

            <div class="alert success">

                <i class="fa-solid fa-circle-check"></i>

                <?= htmlspecialchars($success) ?>

            </div>

        <?php endif; ?>


        <?php if ($error): ?>

            <div class="alert error">

                <i class="fa-solid fa-circle-exclamation"></i>

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>


        <!-- =================================================
             PERSONAL INFORMATION
        ================================================== -->

        <div class="profile-card">

            <div class="profile-card-header">

                <div class="profile-avatar">

                    <i class="fa-solid fa-user"></i>

                </div>

                <div>

                    <h2>Personal Information</h2>

                    <p>
                        Update your account information.
                    </p>

                </div>

            </div>


            <form method="POST">

                <div class="form-grid">


                    <!-- FULL NAME -->

                    <div class="form-group">

                        <label for="name">
                            Full Name
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-user"></i>

                            <input type="text" id="name" name="name"
                                value="<?= htmlspecialchars($data['user_name'] ?? '') ?>" required>

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="form-group">

                        <label for="email">
                            Email Address
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-envelope"></i>

                            <input type="email" id="email" name="email"
                                value="<?= htmlspecialchars($data['user_email'] ?? '') ?>" required>

                        </div>

                    </div>


                    <!-- PHONE -->

                    <div class="form-group">

                        <label for="user_phone">
                            Phone Number
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-phone"></i>

                            <input type="text" id="user_phone" name="user_phone"
                                value="<?= htmlspecialchars($data['user_phone'] ?? '') ?>">

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     PHARMACY INFORMATION
                ================================================== -->

                <div class="section-divider"></div>


                <div class="section-title">

                    <div class="section-icon">

                        <i class="fa-solid fa-prescription-bottle-medical"></i>

                    </div>

                    <div>

                        <h2>Pharmacy Information</h2>

                        <p>
                            Update your registered pharmacy details.
                        </p>

                    </div>

                </div>


                <div class="form-grid">


                    <!-- PHARMACY NAME -->

                    <div class="form-group">

                        <label for="pharmacy_name">
                            Pharmacy Name
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-house-medical"></i>

                            <input type="text" id="pharmacy_name" name="pharmacy_name"
                                value="<?= htmlspecialchars($data['pharmacy_name'] ?? '') ?>" required>

                        </div>

                    </div>


                    <!-- REGISTRATION NUMBER -->

                    <div class="form-group">

                        <label for="registration_no">
                            Registration Number
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-id-card"></i>

                            <input type="text" id="registration_no" name="registration_no"
                                value="<?= htmlspecialchars($data['registration_no'] ?? '') ?>">

                        </div>

                    </div>


                    <!-- PHARMACY EMAIL -->

                    <div class="form-group">

                        <label for="pharmacy_email">
                            Pharmacy Email
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-envelope"></i>

                            <input type="email" id="pharmacy_email" name="pharmacy_email"
                                value="<?= htmlspecialchars($data['pharmacy_email'] ?? '') ?>">

                        </div>

                    </div>


                    <!-- PHARMACY PHONE -->

                    <div class="form-group">

                        <label for="pharmacy_phone">
                            Pharmacy Phone
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-phone"></i>

                            <input type="text" id="pharmacy_phone" name="pharmacy_phone"
                                value="<?= htmlspecialchars($data['pharmacy_phone'] ?? '') ?>">

                        </div>

                    </div>


                    <!-- PAN -->

                    <div class="form-group">

                        <label for="pan_no">
                            PAN Number
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-file-invoice"></i>

                            <input type="text" id="pan_no" name="pan_no"
                                value="<?= htmlspecialchars($data['pan_no'] ?? '') ?>">

                        </div>

                    </div>


                    <!-- PROVINCE -->

                    <div class="form-group">

                        <label for="province">
                            Province
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-map"></i>

                            <input type="text" id="province" name="province"
                                value="<?= htmlspecialchars($data['province'] ?? '') ?>">

                        </div>

                    </div>


                    <!-- DISTRICT -->

                    <div class="form-group">

                        <label for="district">
                            District
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-location-dot"></i>

                            <input type="text" id="district" name="district"
                                value="<?= htmlspecialchars($data['district'] ?? '') ?>">

                        </div>

                    </div>


                    <!-- MUNICIPALITY -->

                    <div class="form-group">

                        <label for="municipality">
                            Municipality
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-building"></i>

                            <input type="text" id="municipality" name="municipality"
                                value="<?= htmlspecialchars($data['municipality'] ?? '') ?>">

                        </div>

                    </div>


                    <!-- WARD -->

                    <div class="form-group">

                        <label for="ward">
                            Ward Number
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-hashtag"></i>

                            <input type="number" id="ward" name="ward" min="1"
                                value="<?= htmlspecialchars($data['ward'] ?? '') ?>">

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     ACTIONS
                ================================================== -->

                <div class="form-actions">

                    <a href="profile.php" class="btn cancel-btn">
                        Cancel
                    </a>

                    <button type="submit" class="btn save-btn">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>