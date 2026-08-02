<?php
$title = "Pharmacist Dashboard";
include 'partials/header.php';
include '../../config/conn.php';
?>

<!-- dashboard css -->
<link rel="stylesheet" href="../../config/dashboard.css">

<div class="dashboard-content">

    <div class="card blue">
        <i class="fa-solid fa-capsules"></i>
        <div>
            <h4>Total Medicines</h4>
            <h2>1832</h2>
        </div>
    </div>

    <!-- <div class="card green">
        <i class="fa-solid fa-layer-group"></i>
        <div>
            <h4>Total Categories</h4>
            <h2>23</h2>
        </div>
    </div> -->

    <div class="card purple">
        <i class="fa-solid fa-boxes-stacked"></i>
        <div>
            <h4>Total Stock Quantity</h4>
            <h2>123</h2>
        </div>
    </div>

    <div class="card orange">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <h4>Low Stock Medicines</h4>
            <h2>45</h2>
        </div>
    </div>

    <div class="card yellow">
        <i class="fa-solid fa-clock"></i>
        <div>
            <h4>Expiring Medicines</h4>
            <h2>20</h2>
        </div>
    </div>

    <div class="card red">
        <i class="fa-solid fa-ban"></i>
        <div>
            <h4>Expired Medicines</h4>
            <h2>5</h2>
        </div>
    </div>

    <!-- <div class="card cyan">
        <i class="fa-solid fa-cart-shopping"></i>
        <div>
            <h4>Pending Orders</h4>
            <h2>5</h2>
        </div>
    </div> -->

    <div class="card teal">
        <i class="fa-solid fa-truck-fast"></i>
        <div>
            <h4>Orders Received</h4>
            <h2>12</h2>
        </div>
    </div>

</div>



<?php
include 'partials/footer.php';
?>

<!-- <script src="dashboard.js"></script> -->