<?php
$title = "Add Medicine";
require_once __DIR__ . "/../../config/conn.php";

$message = "";
$error = "";


// Fetch Categories 
$categories = $conn->query("SELECT id, name FROM categories WHERE status='active' ORDER BY name");
// Fetch Dosage Forms 
$dosageForms = $conn->query("SELECT id, name FROM dosage_forms WHERE status='active' ORDER BY name");
// Fetch Manufacturers 
$manufacturers = $conn->query("SELECT id, name FROM manufacturers WHERE status='active' ORDER BY name");
// Fetch Batches 
$batches = $conn->query("SELECT id, batch_number FROM batches WHERE status='active' ORDER BY batch_number");

// Function to keep old values
function old($key, $default = "")
{
    return htmlspecialchars($_POST[$key] ?? $default);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $generic_name = trim($_POST['generic_name']);
    $brand_name = trim($_POST['brand_name']);
    $category_id = $_POST['category_id'];
    $dosage_form_id = $_POST['dosage_form_id'];
    $manufacturer_id = $_POST['manufacturer_id'];
    $batch_id = !empty($_POST['batch_id']) ? $_POST['batch_id'] : null;
    $status = $_POST['status'];
    $description = trim($_POST['description']);
    $strength = trim($_POST['strength']);

    $pack_size = ($_POST['pack_size_per_strip'] === "")
        ? null
        : $_POST['pack_size_per_strip'];

    $unit = $_POST['unit'];
    $quantity = $_POST['quantity'];
    $min_stock = $_POST['min_stock_level'];

    $mfg_date = !empty($_POST['mfg_date']) ? $_POST['mfg_date'] : null;
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null;

    $cost_price = $_POST['cost_price'];
    $selling_price = $_POST['selling_price'];

    // IMAGE
    $imageName = null;

    if (!empty($_FILES['image']['name'])) {

        $uploadDir = "../../uploads/medicines/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . "_" . basename($_FILES["image"]["name"]);

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            $uploadDir . $imageName
        );
    }

    $stmt = $conn->prepare("
        INSERT INTO medicines
        (
            category_id,
            dosage_form_id,
            manufacturer_id,
            batch_id,
            generic_name,
            brand_name,
            description,
            strength,
            pack_size_per_strip,
            unit,
            image,
            quantity,
            min_stock_level,
            status,
            mfg_date,
            expiry_date,
            cost_price,
            selling_price
        )
        VALUES
        (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "iiiissssiisiiissdd",
        $category_id,
        $dosage_form_id,
        $manufacturer_id,
        $batch_id,
        $generic_name,
        $brand_name,
        $description,
        $strength,
        $pack_size,
        $unit,
        $imageName,
        $quantity,
        $min_stock,
        $status,
        $mfg_date,
        $expiry_date,
        $cost_price,
        $selling_price
    );

    if ($stmt->execute()) {

        $message = "Medicine added successfully.";

        // Clear old values after successful insert
        $_POST = [];

    } else {

        $error = "Failed to add medicine.";
    }

    $stmt->close();
}
?>

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
        max-width: 900px;
        margin: auto;
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
    }

    .title {
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
    textarea,
    select {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        outline: none;
        font-size: 15px;
    }

    input:focus,
    textarea:focus,
    select:focus {
        border-color: #00B894;
        box-shadow: 0 0 5px rgba(0, 184, 148, .3);
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .add_medicine {
        margin-top: 20px;
        padding: 12px;
        width: 100%;
        border: none;
        border-radius: 25px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: .3s;
    }

    .add_medicine:hover {
        transform: scale(1.02);
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


<div class="container">

    <h2 class="title">Add Medicine</h2>

    <?php if ($message): ?>
        <div style="color:green;margin-bottom:20px;">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="color:red;margin-bottom:20px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">

        <div class="grid">

            <!-- Generic Name -->
            <div class="input-group">
                <label>Generic Name</label>
                <input type="text" name="generic_name" value="<?= old('generic_name') ?>" required>
            </div>

            <!-- Brand Name -->
            <div class="input-group">
                <label>Brand Name</label>
                <input type="text" name="brand_name" value="<?= old('brand_name') ?>" required>
            </div>

            <!-- Category -->
            <div class="input-group">
                <label>Category</label>

                <select name="category_id" required>
                    <option value="">Select Category</option>

                    <?php while ($row = $categories->fetch_assoc()): ?>

                        <option value="<?= $row['id'] ?>" <?= old('category_id') == $row['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['name']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>
            </div>

            <!-- Dosage Form -->
            <div class="input-group">
                <label>Dosage Form</label>

                <select name="dosage_form_id" id="dosage_form" required>

                    <option value="">Select Dosage Form</option>

                    <?php while ($row = $dosageForms->fetch_assoc()): ?>

                        <option value="<?= $row['id'] ?>" <?= old('dosage_form_id') == $row['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['name']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <!-- Manufacturer -->
            <div class="input-group"> <label>Manufacturer</label> <input type="text" name="manufacturer_id" required>
            </div>

            <!-- Batch -->
            <div class="input-group"> <label>Batch</label> <input type="text" name="batch_id" required> </div>

            <!-- Strength -->
            <div class="input-group">
                <label>Strength</label>
                <input type="text" name="strength" value="<?= old('strength') ?>" placeholder="500 mg">
            </div>

            <!-- Pack Size -->
            <div class="input-group">
                <label>Pack Size Per Strip</label>
                <input type="number" name="pack_size_per_strip" min="1" value="<?= old('pack_size_per_strip') ?>">
            </div>

            <!-- Unit -->
            <div class="input-group">
                <label>Unit</label>
                <input type="number" name="unit" value="<?= old('unit') ?>" required>
            </div>

            <!-- Quantity -->
            <div class="input-group">
                <label>Quantity</label>
                <input type="number" name="quantity" value="<?= old('quantity', 0) ?>">
            </div>

            <!-- Minimum Stock -->
            <div class="input-group">
                <label>Minimum Stock Level</label>
                <input type="number" name="min_stock_level" value="<?= old('min_stock_level', 10) ?>">
            </div>

            <!-- Status -->
            <div class="input-group">
                <label>Status</label>

                <select name="status">

                    <option value="in stock" <?= old('status', 'in stock') == 'in stock' ? 'selected' : '' ?>>
                        In Stock
                    </option>

                    <option value="out of stock" <?= old('status') == 'out of stock' ? 'selected' : '' ?>>
                        Out Of Stock
                    </option>

                </select>

            </div>

            <!-- Manufacturing Date -->
            <div class="input-group">
                <label>Manufacturing Date</label>
                <input type="date" name="mfg_date" value="<?= old('mfg_date') ?>">
            </div>

            <!-- Expiry Date -->
            <div class="input-group">
                <label>Expiry Date</label>
                <input type="date" name="expiry_date" value="<?= old('expiry_date') ?>">
            </div>

            <!-- Cost Price -->
            <div class="input-group">
                <label>Cost Price</label>
                <input type="number" step="0.01" name="cost_price" value="<?= old('cost_price') ?>" required>
            </div>

            <!-- Selling Price -->
            <div class="input-group">
                <label>Selling Price</label>
                <input type="number" step="0.01" name="selling_price" value="<?= old('selling_price') ?>" required>
            </div>

            <!-- Image -->
            <div class="input-group">
                <label>Medicine Image</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <!-- Description -->
            <div class="input-group full">
                <label>Description</label>
                <textarea name="description"><?= old('description') ?></textarea>
            </div>

        </div>

        <button class="add_medicine" type="submit">Add Medicine</button>

    </form>

</div>


<!--  include 'partials/footer.php'; -->