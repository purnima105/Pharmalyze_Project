<?php
$title = "Pharmacist Dashboard";
include 'partials/header.php';
include '../../config/conn.php';

/* Dashboard Statistics */

// Total Medicines
$totalMedicines = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM medicines"
))['total'];

// Total Categories
$totalCategories = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM categories"
))['total'];

// Total Stock Quantity
$totalStock = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(quantity) AS total FROM medicines"
))['total'] ?? 0;

// Low Stock
$lowStock = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM medicines WHERE quantity <= 20"
))['total'];

// Expiring Medicines (Next 30 Days)
$expiring = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
FROM medicines
WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)"
))['total'];

// Expired Medicines
$expired = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
FROM medicines
WHERE expiry_date < CURDATE()"
))['total'];

// Pending Orders
$pendingOrders = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
FROM orders
WHERE status='Pending'"
))['total'];

// Orders Received
$receivedOrders = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
FROM orders
WHERE status='Received'"
))['total'];
?>

<!-- dashboard css -->
<link rel="stylesheet" href="dashboard.css">

<div class="dashboard-content">

    <div class="card blue">
        <i class="fa-solid fa-capsules"></i>
        <div>
            <h4>Total Medicines</h4>
            <h2><?= $totalMedicines ?></h2>
        </div>
    </div>

    <div class="card green">
        <i class="fa-solid fa-layer-group"></i>
        <div>
            <h4>Total Categories</h4>
            <h2><?= $totalCategories ?></h2>
        </div>
    </div>

    <div class="card purple">
        <i class="fa-solid fa-boxes-stacked"></i>
        <div>
            <h4>Total Stock Quantity</h4>
            <h2><?= number_format($totalStock) ?></h2>
        </div>
    </div>

    <div class="card orange">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <h4>Low Stock Medicines</h4>
            <h2><?= $lowStock ?></h2>
        </div>
    </div>

    <div class="card yellow">
        <i class="fa-solid fa-clock"></i>
        <div>
            <h4>Expiring Medicines</h4>
            <h2><?= $expiring ?></h2>
        </div>
    </div>

    <div class="card red">
        <i class="fa-solid fa-ban"></i>
        <div>
            <h4>Expired Medicines</h4>
            <h2><?= $expired ?></h2>
        </div>
    </div>

    <div class="card cyan">
        <i class="fa-solid fa-cart-shopping"></i>
        <div>
            <h4>Pending Orders</h4>
            <h2><?= $pendingOrders ?></h2>
        </div>
    </div>

    <div class="card teal">
        <i class="fa-solid fa-truck-fast"></i>
        <div>
            <h4>Orders Received</h4>
            <h2><?= $receivedOrders ?></h2>
        </div>
    </div>

</div>



<?php
include 'partials/footer.php';
?>

<!-- <script src="dashboard.js"></script> -->