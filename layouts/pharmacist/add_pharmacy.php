<?php
session_start();
require_once "../../config/conn.php";

// Allow only pharmacists
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != "pharmacist") {
    header("Location: ../../auth/sign_in.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];

    $pharmacy_name = trim($_POST['pharmacy_name']);
    $registration_number = trim($_POST['registration_number']);
    $pan_vat = trim($_POST['pan_vat']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $province = trim($_POST['province']);
    $district = trim($_POST['district']);
    $municipality = trim($_POST['municipality']);
    $ward = trim($_POST['ward']);
    $address = trim($_POST['address']);
    $owner_name = trim($_POST['owner_name']);

    $stmt = $conn->prepare("
        INSERT INTO pharmacies
        (
            user_id,
            pharmacy_name,
            registration_number,
            pan_vat,
            phone,
            email,
            province,
            district,
            municipality,
            ward,
            address,
            owner_name
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isssssssssss",
        $user_id,
        $pharmacy_name,
        $registration_number,
        $pan_vat,
        $phone,
        $email,
        $province,
        $district,
        $municipality,
        $ward,
        $address,
        $owner_name
    );

    if($stmt->execute()){
        header("Location: index.php");
        exit();
    }else{
        $error = "Unable to save pharmacy details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>Add Pharmacy</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f4f7fb;
    padding:40px;
}

.container{

    max-width:850px;
    margin:auto;

    background:#fff;

    padding:35px;

    border-radius:12px;

    box-shadow:0 10px 30px rgba(0,0,0,.08);

}

h2{

    margin-bottom:25px;

    color:#1b5e20;

}

.grid{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:20px;

}

.input-group{

    display:flex;

    flex-direction:column;

}

.input-group.full{

    grid-column:1/3;

}

label{

    margin-bottom:8px;

    font-weight:600;

}

input,
textarea{

    padding:12px;

    border:1px solid #ccc;

    border-radius:8px;

    outline:none;

}

textarea{

    resize:vertical;

    min-height:100px;

}

button{

    margin-top:30px;

    padding:14px;

    width:100%;

    border:none;

    border-radius:8px;

    background:#2e7d32;

    color:white;

    font-size:16px;

    cursor:pointer;

}

button:hover{

    background:#1b5e20;

}

.error{

    color:red;

    margin-bottom:15px;

}

@media(max-width:700px){

.grid{

grid-template-columns:1fr;

}

.input-group.full{

grid-column:auto;

}

}

</style>

</head>

<body>

<div class="container">

<h2>Add Pharmacy Information</h2>

<?php if($error!=""){ ?>

<p class="error"><?php echo $error; ?></p>

<?php } ?>

<form method="POST">

<div class="grid">

<div class="input-group">

<label>Pharmacy Name</label>

<input
type="text"
name="pharmacy_name"
required>

</div>

<div class="input-group">

<label>Registration Number</label>

<input
type="text"
name="registration_number"
required>

</div>

<div class="input-group">

<label>PAN / VAT Number</label>

<input
type="text"
name="pan_vat">

</div>

<div class="input-group">

<label>Owner Name</label>

<input
type="text"
name="owner_name"
required>

</div>

<div class="input-group">

<label>Phone</label>

<input
type="text"
name="phone"
required>

</div>

<div class="input-group">

<label>Email</label>

<input
type="email"
name="email">

</div>

<div class="input-group">

<label>Province</label>

<input
type="text"
name="province">

</div>

<div class="input-group">

<label>District</label>

<input
type="text"
name="district">

</div>

<div class="input-group">

<label>Municipality</label>

<input
type="text"
name="municipality">

</div>

<div class="input-group">

<label>Ward No.</label>

<input
type="text"
name="ward">

</div>

<div class="input-group full">

<label>Full Address</label>

<textarea
name="address"></textarea>

</div>

</div>

<button type="submit">
Save Pharmacy
</button>

</form>

</div>

</body>
</html>