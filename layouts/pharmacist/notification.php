<?php
$title = "Notification";
include 'partials/header.php';
?>

<!-- notification css -->
<link rel="stylesheet" href="notification.css">

<main class="notification-page">

    <div class="notification-header">
        <h2>Notifications</h2>
        <button class="mark-all">Mark All as Read</button>
    </div>

    <div class="notification-list">

        <!-- Notification 1 -->
        <div class="notification-card unread">
            <div class="notification-content">
                <h4>Low Stock Alert</h4>
                <p>Paracetamol 500mg is running low. Only 8 units left.</p>
                <span>5 minutes ago</span>
            </div>

        </div>

        <!-- Notification 2 -->
        <div class="notification-card">
            <div class="notification-content">
                <h4>Order Delivered</h4>
                <p>Your order #PHM1025 has been delivered successfully.</p>
                <span>1 hour ago</span>
            </div>


        </div>

        <!-- Notification 3 -->
        <div class="notification-card unread">
            <div class="notification-content">
                <h4>Medicine Expiring Soon</h4>
                <p>Amoxicillin 250mg will expire in 7 days.</p>
                <span>Yesterday</span>
            </div>


        </div>
        <div class="notification-card unread">
            <div class="notification-content">
                <h4>Medicine Expiring Soon</h4>
                <p>Amoxicillin 250mg will expire in 7 days.</p>
                <span>Yesterday</span>
            </div>

        </div>

    </div>

</main>


<?php
include 'partials/footer.php';
?>