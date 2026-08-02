<?php
session_start();
require_once __DIR__ . "/../../config/conn.php";

// Allow only pharmacists
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != "Pharmacist") {
    header("Location: ../../auth/sign_in.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];

    $name = trim($_POST['pharmacy_name']);
    $registration_no = trim($_POST['registration_number']);
    $pan_no = trim($_POST['pan_vat']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $province = trim($_POST['province']);
    $district = trim($_POST['district']);
    $municipality = trim($_POST['municipality']);
    $ward = (int)$_POST['ward'];
    $street = trim($_POST['address']);
    $created_by = $user_id;

    $stmt = $conn->prepare("
        INSERT INTO pharmacies
        (
            name,
            registration_no,
            email,
            phone,
            pan_no,
            province,
            district,
            municipality,
            ward,
            street,
            created_by,
            user_id
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssssssii",
        $name,
        $registration_no,
        $email,
        $phone,
        $pan_no,
        $province,
        $district,
        $municipality,
        $ward,
        $street,
        $created_by,
        $user_id
    );

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Unable to save pharmacy details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Add Pharmacy | Pharmalyze</title>

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(to right, var(--light), var(--text));
            padding: 40px;
        }

        :root {
            --primary: #00B894;
            --secondary: #0984E3;
            --dark: #111827;
            --light: #f8fafc;
            --text: #374151;
            --shadow: 0 15px 40px rgba(0, 0, 0, .08);
            --radius: 20px;
        }

        .container {
            max-width: 850px;
            margin: auto;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 26px;
            color: var(--primary);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group.full {
            grid-column: 1/3;
        }

        label {
            margin-bottom: 8px;
            font-weight: 600;
        }

        input,
        textarea {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(51, 67, 243, 0.4);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 25px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transform: 0.2s ease,
        }

        button:hover {
            background-color: var(--primary-hover);
            transform: scale(1.05);
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        @media(max-width:700px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .input-group.full {
                grid-column: auto;
            }
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Add Pharmacy Information</h2>

        <?php if ($error != "") { ?>

            <p class="error"><?php echo $error; ?></p>

        <?php } ?>

        <form method="POST">

            <div class="grid">

                <div class="input-group">

                    <label>Pharmacy Name</label>

                    <input type="text" name="pharmacy_name" required>

                </div>

                <div class="input-group">

                    <label>Registration Number</label>

                    <input type="text" name="registration_number" required>

                </div>

                <div class="input-group">

                    <label>PAN / VAT Number</label>

                    <input type="text" name="pan_vat">

                </div>

                <div class="input-group">

                    <label>Owner Name</label>

                    <input type="text" name="owner_name" required>

                </div>

                <div class="input-group">

                    <label>Phone</label>

                    <input type="text" name="phone" required>

                </div>

                <div class="input-group">

                    <label>Email</label>

                    <input type="email" name="email">

                </div>

                <div class="input-group">

                    <label>Province</label>

                    <input type="text" name="province">

                </div>

                <div class="input-group">

                    <label>District</label>

                    <input type="text" name="district">

                </div>

                <div class="input-group">

                    <label>Municipality</label>

                    <input type="text" name="municipality">

                </div>

                <div class="input-group">

                    <label>Ward No.</label>

                    <input type="text" name="ward">

                </div>

                <div class="input-group full">

                    <label>Full Address</label>

                    <textarea name="address"></textarea>

                </div>

            </div>

            <button type="submit">
                Add Pharmacy
            </button>

        </form>

    </div>

</body>

</html>