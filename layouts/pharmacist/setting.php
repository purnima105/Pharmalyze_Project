<?php

session_start();

require_once __DIR__ . "/../../config/conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

?>

<?php

$title = "Settings";

include 'partials/header.php';

?>

<link rel="stylesheet" href="../../auth/setting.css">


<div class="settings-container">

    <!-- PAGE HEADER -->
    <div class="settings-header">

        <div>
            <h1>Settings</h1>
            <p>Manage your account and Pharmalyze preferences.</p>
        </div>

    </div>


    <!-- SETTINGS LAYOUT -->
    <div class="settings-layout">


        <!-- SETTINGS MENU -->
        <aside class="settings-menu">

            <div class="menu-title">
                Settings
            </div>

            <a href="#account" class="settings-menu-item active">
                <span class="menu-icon">
                    <i class="fa-solid fa-user"></i>
                </span>

                <span>
                    <strong>Account</strong>
                    <small>Profile information</small>
                </span>
            </a>


            <a href="#security" class="settings-menu-item">
                <span class="menu-icon">
                    <i class="fa-solid fa-lock"></i>
                </span>

                <span>
                    <strong>Security</strong>
                    <small>Password & security</small>
                </span>
            </a>


            <a href="#notifications" class="settings-menu-item">
                <span class="menu-icon">
                    <i class="fa-solid fa-bell"></i>
                </span>

                <span>
                    <strong>Notifications</strong>
                    <small>Alert preferences</small>
                </span>
            </a>


            <a href="#appearance" class="settings-menu-item">
                <span class="menu-icon">
                    <i class="fa-solid fa-palette"></i>
                </span>

                <span>
                    <strong>Appearance</strong>
                    <small>Customize interface</small>
                </span>
            </a>


            <a href="#system" class="settings-menu-item">
                <span class="menu-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </span>

                <span>
                    <strong>System</strong>
                    <small>System information</small>
                </span>
            </a>

        </aside>



        <!-- SETTINGS CONTENT -->
        <main class="settings-content">


            <!-- ACCOUNT -->
            <section class="settings-card" id="account">

                <div class="card-header">

                    <div class="card-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>
                        <h2>Account Settings</h2>
                        <p>Manage your personal account information.</p>
                    </div>

                </div>


                <div class="account-preview">

                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=00B894&color=fff&size=100"
                        alt="Profile">

                    <div class="account-info">

                        <h3>
                            <?= htmlspecialchars($user['name'] ?? 'User') ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($user['email'] ?? '') ?>
                        </p>

                        <span>
                            <?= htmlspecialchars(ucfirst($user['role'] ?? 'User')) ?>
                        </span>

                    </div>

                    <a href="profile" class="outline-btn">
                        View Profile
                    </a>

                </div>


                <div class="setting-row">

                    <div class="setting-text">
                        <h3>Email Address</h3>
                        <p>Your registered email address.</p>
                    </div>

                    <strong>
                        <?= htmlspecialchars($user['email'] ?? 'Not provided') ?>
                    </strong>

                </div>


                <div class="setting-row">

                    <div class="setting-text">
                        <h3>Phone Number</h3>
                        <p>Your registered contact number.</p>
                    </div>

                    <strong>
                        <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?>
                    </strong>

                </div>

            </section>



            <!-- SECURITY -->
            <section class="settings-card" id="security">

                <div class="card-header">

                    <div class="card-icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>

                    <div>
                        <h2>Security</h2>
                        <p>Keep your Pharmalyze account secure.</p>
                    </div>

                </div>


                <div class="setting-row">

                    <div class="setting-text">

                        <h3>Password</h3>

                        <p>
                            Change your account password regularly
                            to keep your account secure.
                        </p>

                    </div>

                    <a href="change-password.php" class="outline-btn">
                        Change Password
                    </a>

                </div>


                <div class="security-status">

                    <i class="fa-solid fa-shield-halved"></i>

                    <div>
                        <strong>Your account is protected</strong>
                        <p>
                            Never share your password with anyone.
                        </p>
                    </div>
                </div>
            </section>

            <!-- NOTIFICATIONS -->
            <section class="settings-card" id="notifications">

                <div class="card-header">

                    <div class="card-icon">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <h2>Notifications</h2>
                        <p>Choose which alerts you want to receive.</p>
                    </div>
                </div>

                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="notification-icon">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <div>
                            <h3>Low Stock Alerts</h3>
                            <p>
                                Notify me when medicine stock reaches
                                the minimum stock level.
                            </p>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="notification-icon">
                            <i class="fa-solid fa-calendar-xmark"></i>
                        </div>
                        <div>
                            <h3>Expiry Alerts</h3>
                            <p>
                                Notify me about medicines approaching
                                their expiry date.
                            </p>
                        </div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="toggle-row">

                    <div class="toggle-info">

                        <div class="notification-icon">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>

                        <div>

                            <h3>Purchase Updates</h3>

                            <p>
                                Receive notifications about purchase
                                and supplier updates.
                            </p>

                        </div>

                    </div>

                    <label class="switch">

                        <input type="checkbox" checked>

                        <span class="slider"></span>

                    </label>

                </div>

            </section>



            <!-- APPEARANCE -->
            <section class="settings-card" id="appearance">

                <div class="card-header">

                    <div class="card-icon">
                        <i class="fa-solid fa-palette"></i>
                    </div>

                    <div>
                        <h2>Appearance</h2>
                        <p>Customize how Pharmalyze looks.</p>
                    </div>

                </div>


                <div class="appearance-row">

                    <div>

                        <h3>Theme</h3>

                        <p>
                            Choose your preferred dashboard appearance.
                        </p>

                    </div>


                    <div class="theme-options">

                        <label class="theme-option active">

                            <input type="radio" name="theme" value="light" checked>

                            <span>
                                <i class="fa-solid fa-sun"></i>
                                Light
                            </span>

                        </label>


                        <label class="theme-option">

                            <input type="radio" name="theme" value="dark">

                            <span>
                                <i class="fa-solid fa-moon"></i>
                                Dark
                            </span>

                        </label>

                    </div>

                </div>

            </section>



            <!-- SYSTEM -->
            <section class="settings-card" id="system">

                <div class="card-header">

                    <div class="card-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <div>
                        <h2>System Information</h2>
                        <p>Information about your Pharmalyze system.</p>
                    </div>

                </div>


                <div class="system-grid">

                    <div class="system-item">

                        <span>Application</span>

                        <strong>Pharmalyze</strong>

                    </div>


                    <div class="system-item">

                        <span>Version</span>

                        <strong>1.0.0</strong>

                    </div>


                    <div class="system-item">

                        <span>Environment</span>

                        <strong>Production</strong>

                    </div>


                    <div class="system-item">

                        <span>Database</span>

                        <strong>
                            <i class="fa-solid fa-circle"></i>
                            Connected
                        </strong>

                    </div>

                </div>

            </section>



            <!-- DANGER ZONE -->
            <section class="danger-card">

                <div>

                    <div class="danger-title">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                        <h2>Account Actions</h2>

                    </div>

                    <p>
                        Make sure you save your work before leaving
                        your Pharmalyze account.
                    </p>

                </div>


                <a href="logout.php" class="logout-btn">

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Logout

                </a>

            </section>


        </main>

    </div>

</div>


<?php

include 'partials/footer.php';

?>