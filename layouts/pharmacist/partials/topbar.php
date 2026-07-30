<!-- MAIN -->
<?php
// session_start();
include '../../config/conn.php';   // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// User information
$name = $user['name'];
$role = ucfirst($user['role']); // Admin, Pharmacist, Supplier
?>

<main class="main">

    <!-- NAVBAR -->
    <nav class="navbar">

        <!-- Left Side -->
        <div class="nav-left">
            <h3>Welcome Back, <?= htmlspecialchars($name) ?></h3>
        </div>

        <!-- Right Side -->
        <div class="nav-right">

            <!-- Notifications -->
            <div class="notification-wrapper">

                <button class="notification-btn" id="notificationBtn">
                    <i class="fa-solid fa-bell fa-2xl"></i>
                    <span class="badge">3</span>
                </button>

                <div class="notification-popup" id="notificationPopup">

                    <h4>Notifications</h4>

                    <div class="notification-item">
                        Low stock alert for Paracetamol
                    </div>

                    <div class="notification-item">
                        5 medicines expiring this week
                    </div>

                    <div class="notification-item">
                        New supplier order delivered
                    </div>

                    <a href="notification.php">View More</a>

                </div>

            </div>

            <!-- Profile -->
            <div class="profile-wrapper">

                <div class="profile" id="profileBtn">

                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($name) ?>&background=00B894&color=fff">

                    <div>
                        <h5><?= htmlspecialchars($name) ?></h5>
                        <small><?= htmlspecialchars($role) ?></small>
                    </div>

                    <i class="fa-solid fa-chevron-down"></i>

                </div>

                <div class="profile-popup" id="profilePopup">

                    <a href="profile.php">
                        <i class="fa-solid fa-user"></i>
                        My Profile
                    </a>

                    <a href="setting.php">
                        <i class="fa-solid fa-gear"></i>
                        Settings
                    </a>

                    <a href="../../auth/logout.php" class="logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </a>

                </div>

            </div>

        </div>

    </nav>

    <!-- CONTENT STARTS HERE -->