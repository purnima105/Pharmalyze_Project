<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


// Connect to MySQL server
$conn = mysqli_connect(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
// $sql = "CREATE DATABASE IF NOT EXISTS {$_ENV['DB_NAME']}";

// if (!mysqli_query($conn, $sql)) {
//     die("Database creation failed: " . mysqli_error($conn));
// }

// // Select database
// mysqli_select_db($conn, $_ENV['DB_NAME']);


// email configuration
$smtpHost =$_ENV['SMTP_HOST'];
$smtpUsername =$_ENV['SMTP_USERNAME'];
$smtpPassword = $_ENV['SMTP_PASSWORD'];
$smtpPort =$_ENV['SMTP_PORT'];
$smtpFromEmail =$_ENV['SMTP_FROM'];
$smtpFromName = $_ENV['SMTP_FROM_NAME'];

if(session_status()==PHP_SESSION_NONE){
    session_start();
}


?>