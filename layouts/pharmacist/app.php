<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?? 'Pharmalyze' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
</head>

<body>

    <?php
    include 'partials/sidebar.php';
    include 'partials/topbar.php';
    // substitute of @yield('content')
    echo $content ?? 'dashboard.php';
    ?>

    <script src="dashboard.js"></script>

</body>

</html> 