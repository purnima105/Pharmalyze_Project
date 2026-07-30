<div class="dashboard">

    <?php
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
    ?>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo">
            <i class="fa-solid fa-capsules"></i>
            <span>Pharmalyze</span>
        </div>

        <a href="dashboard" class="<?= $currentPage == 'dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>

        <a href="inventory" class="<?= $currentPage == 'inventory' ? 'active' : '' ?>">
            <i class="fa-solid fa-boxes-stacked"></i>
            Inventory
        </a>

        <a href="purchase" class="<?= $currentPage == 'purchase' ? 'active' : '' ?>">
            <i class="fa-solid fa-cart-shopping"></i>
            Purchase
        </a>

        <a href="report" class="<?= $currentPage == 'report' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-column"></i>
            Reports
        </a>

        <a href="setting" class="<?= $currentPage == 'setting' ? 'active' : '' ?>">
            <i class="fa-solid fa-gear"></i>
            Settings
        </a>

    </aside>