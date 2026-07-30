<?php

// session_start();
include '../../config/conn.php';
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<?php
$title = "My Profile";
include 'partials/header.php';
?>

<link rel="stylesheet" href="profile.css">

<div class="profile-container">

    <div class="profile-card">

        <div class="profile-header">

            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=00B894&color=fff&size=150">

            <h2><?= htmlspecialchars($user['name']) ?></h2>

            <span><?= ucfirst($user['role']) ?></span>

        </div>

        <form action="update-profile.php" method="POST">

            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <div class="form-group">

                <label>Full Name</label>

                <input type="text"
                       value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                       readonly>

            </div>

            <div class="form-group">

                <label>Email</label>

                <input type="email"
                       name="email"
                       value="<?= htmlspecialchars($user['email']?? '') ?>">

            </div>

            <div class="form-group">

                <label>Phone Number</label>

                <input type="text"
                       name="phone"
                       value="<?= htmlspecialchars($user['phone']?? '') ?>">

            </div>

            <div class="form-group">

                <label>Gender</label>

                <select name="gender">

                    <option value="Male"
                    <?= ($user['gender']=="Male")?"selected":"" ?>>
                    Male
                    </option>

                    <option value="Female"
                    <?= ($user['gender']=="Female")?"selected":"" ?>>
                    Female
                    </option>

                    <option value="Other"
                    <?= ($user['gender']=="Other")?"selected":"" ?>>
                    Other
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Address</label>

                <textarea name="address"><?= htmlspecialchars($user['address']) ?></textarea>

            </div>

            <div class="form-group">

                <label>User ID</label>

                <input type="text"
                       value="<?= $user['id'] ?>"
                       readonly>

            </div>

            <div class="form-group">

                <label>Role</label>

                <input type="text"
                       value="<?= ucfirst($user['role']) ?>"
                       readonly>

            </div>

            <button class="save-btn">
                Update Profile
            </button>

        </form>

    </div>

</div>

<?php
include 'partials/footer.php';
?>