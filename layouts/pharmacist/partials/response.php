<?php
// session_start();

// Check if there are any flash messages
$flashMessages = [];

if (isset($_SESSION['success'])) {
    $flashMessages[] = ['type' => 'success', 'message' => $_SESSION['success']];
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    $flashMessages[] = ['type' => 'error', 'message' => $_SESSION['error']];
    unset($_SESSION['error']);
}

if (isset($_SESSION['warning'])) {
    $flashMessages[] = ['type' => 'warning', 'message' => $_SESSION['warning']];
    unset($_SESSION['warning']);
}

// Convert to JSON for JavaScript
$flashMessagesJson = json_encode($flashMessages);
?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">

<!-- Your page content here -->

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
    // Get flash messages from PHP
    const flashMessages = <?php echo $flashMessagesJson; ?>;

    // Display each message
    flashMessages.forEach(msg => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',        // Top right corner
            showConfirmButton: false,   // No OK button
            timer: 3000,                // 3 seconds
            timerProgressBar: true,     // Show progress bar
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: msg.type,             // success, error, or warning
            title: msg.message
        });
    });
</script>