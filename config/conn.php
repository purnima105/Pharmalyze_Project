<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


// Connect to MySQL server
$conn = mysqli_connect(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS {$_ENV['DB_NAME']}";

if (!mysqli_query($conn, $sql)) {
    die("Database creation failed: " . mysqli_error($conn));
}

// Select database
mysqli_select_db($conn, $_ENV['DB_NAME']);

// Create products table
$table = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price INT,
    quantity INT
)";

mysqli_query($conn, $table);

// Create users table
$table1 = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM ('admin', 'pharmacist', 'supplier'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

mysqli_query($conn, $table1);



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