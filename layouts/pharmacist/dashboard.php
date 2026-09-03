<?php
$title = "Pharmacist Dashboard";
include 'partials/header.php';
include '../../config/conn.php';
?>

<!-- dashboard css -->
<link rel="stylesheet" href="../../css/dashboard.css">
<div class="main-dashboard">
    <h2>Pharmacist Dashboard</h2>
    <div class="dashboard-content">

        <div class="card blue">
            <i class="fa-solid fa-capsules"></i>
            <div>
                <h4>Total Medicines</h4>
                <h2>1832</h2>
            </div>
        </div>

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



    <!-- recent -->

    <div class="dashboard-lower">
        <div class="dashboard-panel">
            <div class="panel-header">
                <div>
                    <h3>Recent Sales</h3>
                    <p>Latest medicine sales</p>
                </div>
                <a href="sales.php">View All</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Paracetamol 500mg</td>
                            <td>12</td>
                            <td>Rs. 240</td>
                            <td>23 Aug 2026</td>
                        </tr>
                        <tr>
                            <td>Vitamin C</td>
                            <td>8</td>
                            <td>Rs. 480</td>
                            <td>23 Aug 2026</td>
                        </tr>
                        <tr>
                            <td>Cetirizine 10mg</td>
                            <td>5</td>
                            <td>Rs. 150</td>
                            <td>22 Aug 2026</td>
                        </tr>
                        <tr>
                            <td>Omeprazole 20mg</td>
                            <td>10</td>
                            <td>Rs. 300</td>
                            <td>22 Aug 2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-panel">
            <div class="panel-header">
                <div>
                    <h3>Inventory Alerts</h3>
                    <p>Medicines that need your attention</p>
                </div>
                <a href="notification.php">View All</a>
            </div>

            <div class="alert-list">
                <div class="alert-item low-stock">
                    <div class="alert-icon">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div class="alert-info">
                        <strong>Low Stock</strong>
                        <p>Amoxicillin 500mg is running low. Only 8 units remaining.</p>
                    </div>
                    <a href="inventory.php">Check</a>
                </div>

                <div class="alert-item low-stock">
                    <div class="alert-icon">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div class="alert-info">
                        <strong>Low Stock</strong>
                        <p>Cetirizine 10mg has reached its minimum stock level.</p>
                    </div>
                    <a href="inventory.php">Check</a>
                </div>

                <div class="alert-item expiring">
                    <div class="alert-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="alert-info">
                        <strong>Expiring Soon</strong>
                        <p>Azithromycin 500mg expires within the next 30 days.</p>
                    </div>
                    <a href="inventory.php">Check</a>
                </div>

                <div class="alert-item expired">
                    <div class="alert-icon">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div class="alert-info">
                        <strong>Expired</strong>
                        <p>5 medicines have already passed their expiry date.</p>
                    </div>
                    <a href="inventory.php">Check</a>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-purchases">
        <div class="dashboard-panel">
            <div class="panel-header">
                <div>
                    <h3>Recent Purchases</h3>
                    <p>Latest medicine purchases</p>
                </div>
                <a href="purchase.php">View All</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Paracetamol 500mg</td>
                            <td>ABC Pharma</td>
                            <td>200</td>
                            <td>23 Aug 2026</td>
                            <td><span class="status completed">Received</span></td>
                        </tr>
                        <tr>
                            <td>Amoxicillin 500mg</td>
                            <td>Medicare Suppliers</td>
                            <td>100</td>
                            <td>22 Aug 2026</td>
                            <td><span class="status completed">Received</span></td>
                        </tr>
                        <tr>
                            <td>Cetirizine 10mg</td>
                            <td>HealthCare Pharma</td>
                            <td>150</td>
                            <td>21 Aug 2026</td>
                            <td><span class="status pending">Pending</span></td>
                        </tr>
                        <tr>
                            <td>Azithromycin 500mg</td>
                            <td>ABC Pharma</td>
                            <td>80</td>
                            <td>20 Aug 2026</td>
                            <td><span class="status completed">Received</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>






<?php
include 'partials/footer.php';
?>

<!-- <script src="dashboard.js"></script> -->