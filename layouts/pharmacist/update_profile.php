<?php

session_start();
require_once __DIR__ . "/../../config/conn.php";

$id = $_POST['id'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$gender = $_POST['gender'];
$address = $_POST['address'];

$sql = "UPDATE users
SET
email='$email',
phone='$phone',
gender='$gender',
address='$address'
WHERE id='$id'";

mysqli_query($conn,$sql);

header("Location: profile.php?updated=1");