<?php
$title = "Add Medicine";
// include 'partials/header.php';
require_once __DIR__ . "/../../config/conn.php";

// Fetch Categories
$categories = $conn->query("SELECT id, name FROM categories WHERE status='active' ORDER BY name");

// Fetch Dosage Forms
$dosageForms = $conn->query("SELECT id, name FROM dosage_forms WHERE status='active' ORDER BY name");

// Fetch Manufacturers
$manufacturers = $conn->query("SELECT id, name FROM manufacturers WHERE status='active' ORDER BY name");

// Fetch Batches
$batches = $conn->query("SELECT id, batch_number FROM batches WHERE status='active' ORDER BY batch_number");
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

    <form action="" method="POST" enctype="multipart/form-data">

        <div class="grid">

            <!-- Generic Name -->
            <div class="input-group">
                <label>Generic Name</label>
                <input type="text" name="generic_name" required>
            </div>

            <!-- Brand Name -->
            <div class="input-group">
                <label>Brand Name</label>
                <input type="text" name="brand_name" required>
            </div>

            <!-- Category -->
            <div class="input-group">
                <label>Category</label>

                <select name="category_id" required>
                    <option value="">Select Category</option>

                    <?php while ($row = $categories->fetch_assoc()): ?>

                        <option value="<?= $row['id']; ?>">
                            <?= htmlspecialchars($row['name']); ?>
                        </option>

                    <?php endwhile; ?>

                </select>
            </div>

            <!-- Dosage Form -->
            <div class="input-group">
                <label>Dosage Form</label>

                <select name="dosage_form_id" required>

                    <option value="">Select Dosage Form</option>

                    <?php while ($row = $dosageForms->fetch_assoc()): ?>

                        <option value="<?= $row['id']; ?>">
                            <?= htmlspecialchars($row['name']); ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <!-- Manufacturer -->
            <div class="input-group">
                <label>Manufacturer</label>

                <select name="manufacturer_id" required>

                    <option value="">Select Manufacturer</option>

                    <?php while ($row = $manufacturers->fetch_assoc()): ?>

                        <option value="<?= $row['id']; ?>">
                            <?= htmlspecialchars($row['name']); ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <!-- Batch -->
            <div class="input-group">
                <label>Batch</label>

                <select name="batch_id">

                    <option value="">Select Batch</option>

                    <?php while ($row = $batches->fetch_assoc()): ?>

                        <option value="<?= $row['id']; ?>">
                            <?= htmlspecialchars($row['batch_number']); ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <!-- Strength -->
            <div class="input-group">
                <label>Strength</label>
                <input type="text" name="strength" placeholder="500 mg">
            </div>

            <!-- Pack Size -->
            <div class="input-group">
                <label>Pack Size Per Strip</label>
                <input type="number" name="pack_size_per_strip" min="1">
            </div>

            <!-- Unit -->
            <div class="input-group">
                <label>Unit</label>
                <input type="number" name="unit" required>
            </div>

            <!-- Quantity -->
            <div class="input-group">
                <label>Quantity</label>
                <input type="number" name="quantity" value="0">
            </div>

            <!-- Minimum Stock -->
            <div class="input-group">
                <label>Minimum Stock Level</label>
                <input type="number" name="min_stock_level" value="10">
            </div>

            <!-- Status -->
            <div class="input-group">
                <label>Status</label>

                <select name="status">

                    <option value="in stock">In Stock</option>
                    <option value="out of stock">Out Of Stock</option>

                </select>

            </div>

            <!-- Manufacturing Date -->
            <div class="input-group">
                <label>Manufacturing Date</label>
                <input type="date" name="mfg_date">
            </div>

            <!-- Expiry Date -->
            <div class="input-group">
                <label>Expiry Date</label>
                <input type="date" name="expiry_date">
            </div>

            <!-- Cost Price -->
            <div class="input-group">
                <label>Cost Price</label>
                <input type="number" step="0.01" name="cost_price" required>
            </div>

            <!-- Selling Price -->
            <div class="input-group">
                <label>Selling Price</label>
                <input type="number" step="0.01" name="selling_price" required>
            </div>

            <!-- Image -->
            <div class="input-group">
                <label>Medicine Image</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <!-- Description -->
            <div class="input-group full">
                <label>Description</label>
                <textarea name="description"></textarea>
            </div>

        </div>

        <button class="add_medicine" type="submit">Add Medicine</button>

    </form>

</div>

<?php
// include 'partials/footer.php';
?>