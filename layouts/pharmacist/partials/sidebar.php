<div class="dashboard">

    <?php
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
    ?>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <!-- LOGO -->
        <div class="sidebar-logo">

            <div class="logo-icon">
                <img src="../../assets/logo.png" alt="Logo of Pharmalyze" width="50" height="50">
            </div>

            <div class="logo-text">
                <span>Pharmalyze</span>
                <small>Pharmacy Management</small>
            </div>

        </div>


        <!-- MAIN NAVIGATION -->
        <div class="sidebar-section">

            <p class="sidebar-label">
                 MENU
            </p>


            <a href="dashboard" class="sidebar-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>">

                <span class="nav-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span class="nav-text">
                    Dashboard
                </span>

            </a>


            <a href="inventory" class="sidebar-link <?= $currentPage == 'inventory' ? 'active' : '' ?>">

                <span class="nav-icon">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </span>

                <span class="nav-text">
                    Inventory
                </span>

            </a>


            <a href="purchase" class="sidebar-link <?= $currentPage == 'purchase' ? 'active' : '' ?>">

                <span class="nav-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </span>

                <span class="nav-text">
                    Purchase
                </span>

            </a>


            <a href="report" class="sidebar-link <?= $currentPage == 'report' ? 'active' : '' ?>">

                <span class="nav-icon">
                    <i class="fa-solid fa-chart-column"></i>
                </span>

                <span class="nav-text">
                    Reports
                </span>

            </a>


            <a href="setting" class="sidebar-link <?= $currentPage == 'setting' ? 'active' : '' ?>">

                <span class="nav-icon">
                    <i class="fa-solid fa-gear"></i>
                </span>

                <span class="nav-text">
                    Settings
                </span>

            </a>

        </div>


        <!-- BOTTOM AREA -->
        <div class="sidebar-bottom">

            <div class="sidebar-divider"></div>

            <p class="sidebar-label">
                ACCOUNT
            </p>


            <!-- PROFILE -->
            <a href="profile" class="sidebar-link profile-link <?= $currentPage == 'profile' ? 'active' : '' ?>">

                <span class="nav-icon">
                    <i class="fa-solid fa-user"></i>
                </span>

                <span class="nav-text">
                    Profile
                </span>

            </a>


            <!-- LOGOUT -->
            <a href="../../auth/logout" class="sidebar-link logout-link">

                <span class="nav-icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </span>

                <span class="nav-text">
                    Logout
                </span>

            </a>

        </div>

    </aside>