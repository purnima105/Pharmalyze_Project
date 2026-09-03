<?php

$title = "Add Medicine";
require_once __DIR__ . "/../../config/conn.php";

$message = "";
$error = "";

//  Fetch Categories
$categories = $conn->query("
    SELECT id, name
    FROM categories
    WHERE status = 'active'
    ORDER BY name
");

//  Fetch Dosage Forms
$dosageForms = $conn->query("
    SELECT id, name
    FROM dosage_forms
    WHERE status = 'active'
    ORDER BY name
");

// Fetch Manufacturers
$manufacturers = $conn->query("
    SELECT id, name
    FROM manufacturers
    WHERE status = 'active'
    ORDER BY name
");

//  Keep Old Form Values
function old($key, $default = "")
{
    return htmlspecialchars($_POST[$key] ?? $default);
}

//  Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Medicine Information
    $generic_name = trim($_POST['generic_name'] ?? '');
    $brand_name = trim($_POST['brand_name'] ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $dosage_form_id = (int) ($_POST['dosage_form_id'] ?? 0);
    $manufacturer_id = (int) ($_POST['manufacturer_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $strength = trim($_POST['strength'] ?? '');
    $pack_size = (
        isset($_POST['pack_size_per_strip']) &&
        $_POST['pack_size_per_strip'] !== ''
    )
        ? (int) $_POST['pack_size_per_strip']
        : null;

    $min_stock = (int) ($_POST['min_stock_level'] ?? 10);

    $medicine_status = $_POST['medicine_status'] ?? 'active';

    //  Batch Information
    $batch_number = trim($_POST['batch_number'] ?? '');

    $quantity = (int) ($_POST['quantity'] ?? 0);

    $mfg_date = !empty($_POST['mfg_date'])
        ? $_POST['mfg_date']
        : null;

    $expiry_date = !empty($_POST['expiry_date'])
        ? $_POST['expiry_date']
        : null;

    $cost_price = (float) ($_POST['cost_price'] ?? 0);
    $selling_price = (float) ($_POST['selling_price'] ?? 0);
    $batch_status = $_POST['batch_status'] ?? 'active';

    //  Validation
    if (
        empty($generic_name) ||
        empty($brand_name) ||
        $category_id <= 0 ||
        $dosage_form_id <= 0 ||
        $manufacturer_id <= 0 ||
        empty($batch_number)
    ) {

        $error = "Please fill all required fields.";

    } elseif ($quantity < 0) {

        $error = "Quantity cannot be negative.";

    } elseif ($cost_price <= 0 || $selling_price <= 0) {

        $error = "Cost price and selling price must be greater than 0.";

    } elseif ($selling_price < $cost_price) {

        $error = "Selling price cannot be lower than cost price.";

    } elseif (
        !empty($mfg_date) &&
        !empty($expiry_date) &&
        $expiry_date <= $mfg_date
    ) {

        $error = "Expiry date must be after manufacturing date.";

    } else {

        // Image Upload
        $imageName = null;

        if (!empty($_FILES['image']['name'])) {

            $uploadDir = "../../uploads/medicines/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = pathinfo(
                $_FILES["image"]["name"],
                PATHINFO_EXTENSION
            );

            $imageName = time() . "_" . uniqid() . "." . $extension;

            $uploadSuccess = move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                $uploadDir . $imageName
            );

            if (!$uploadSuccess) {
                $error = "Failed to upload medicine image.";
            }
        }

        //  Insert Medicine + Batch
        if (empty($error)) {

            try {
                // Start Transaction
                $conn->begin_transaction();
                // //  Find Manufacturer
                // $manufacturerStmt = $conn->prepare("
                //     SELECT id
                //     FROM manufacturers
                //     WHERE name = ?
                //     LIMIT 1
                // ");

                // $manufacturerStmt->bind_param(
                //     "s",
                //     $manufacturer_name
                // );
                // $manufacturerStmt->execute();
                // $manufacturerResult =
                //     $manufacturerStmt->get_result();

                // if ($manufacturerResult->num_rows > 0) {
                //     $manufacturerRow = $manufacturerResult->fetch_assoc();
                //     $manufacturer_id = (int) $manufacturerRow['id'];
                // } else {
                //     //  New manufacturer
                //     $insertManufacturer = $conn->prepare("
                //         INSERT INTO manufacturers
                //         (
                //             name,
                //             status
                //         )
                //         VALUES
                //         (?, 'active')
                //     ");

                //     $insertManufacturer->bind_param("s", $manufacturer_name);

                //     if (!$insertManufacturer->execute()) {

                //         throw new Exception(
                //             "Failed to add manufacturer: " .
                //             $insertManufacturer->error
                //         );
                //     }

                //     $manufacturer_id =
                //         $conn->insert_id;

                //     $insertManufacturer->close();
                // }

                // $manufacturerStmt->close();

                // Check Duplicate Batch
                $checkBatch = $conn->prepare("
                    SELECT id
                    FROM batches
                    WHERE batch_number = ?
                    LIMIT 1
                ");

                $checkBatch->bind_param(
                    "s",
                    $batch_number
                );

                $checkBatch->execute();

                $batchResult =
                    $checkBatch->get_result();


                if ($batchResult->num_rows > 0) {

                    throw new Exception(
                        "This batch number already exists."
                    );
                }
                $checkBatch->close();

                //  Insert Medicine

                $stmt = $conn->prepare("
                    INSERT INTO medicines
                    (
                        category_id,
                        dosage_form_id,
                        manufacturer_id,
                        generic_name,
                        brand_name,
                        description,
                        strength,
                        pack_size_per_strip,
                        image,
                        min_stock_level,
                        status
                    )
                    VALUES
                    (
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    )
                ");

                $stmt->bind_param(
                    "iiissssisis",
                    $category_id,
                    $dosage_form_id,
                    $manufacturer_id,
                    $generic_name,
                    $brand_name,
                    $description,
                    $strength,
                    $pack_size,
                    $imageName,
                    $min_stock,
                    $medicine_status
                );

                if (!$stmt->execute()) {

                    throw new Exception(
                        "Failed to add medicine: " .
                        $stmt->error
                    );
                }

                $medicine_id = $conn->insert_id;
                $stmt->close();

                //  Insert Batch
                $batchStmt = $conn->prepare("
                    INSERT INTO batches
                    (
                        medicine_id,
                        batch_number,
                        mfg_date,
                        expiry_date,
                        cost_price,
                        selling_price,
                        quantity,
                        status
                    )
                    VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $batchStmt->bind_param(
                    "isssddis",
                    $medicine_id,
                    $batch_number,
                    $mfg_date,
                    $expiry_date,
                    $cost_price,
                    $selling_price,
                    $quantity,
                    $batch_status
                );

                if (!$batchStmt->execute()) {
                    throw new Exception(
                        "Failed to add batch: " .
                        $batchStmt->error
                    );
                }
                $batchStmt->close();
                //  Commit Transaction

                $conn->commit();
                $message =
                    "Medicine and batch added successfully.";
                $_POST = [];
            } catch (Exception $e) {
                $conn->rollback();
                $error = $e->getMessage();
            }
        }
    }
}

?>

<style>
    /* =========================================================
   PHARMALYZE — ADD MEDICINE
   Clean / Professional / Pharmacy Admin UI
   ========================================================= */

    :root {
        --primary: #00a884;
        --primary-dark: #008f70;
        --primary-light: #e9f8f4;

        --text: #17202a;
        --text-secondary: #64748b;
        --muted: #94a3b8;

        --border: #e2e8f0;
        --border-hover: #cbd5e1;

        --background: #f6f8fa;
        --white: #ffffff;

        --success: #16803c;
        --success-bg: #ecfdf3;

        --danger: #dc2626;
        --danger-bg: #fef2f2;

        --shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
    }


    /* =========================================================
   GLOBAL
   ========================================================= */

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: var(--background);
        color: var(--text);
        font-family: Arial, Helvetica, sans-serif;
    }


    /* =========================================================
   MAIN CONTAINER
   ========================================================= */

    .container {
        width: calc(100% - 245px);
        margin-left: 150px;
        padding: 35px 40px 50px;
        min-height: 100vh;
        background: #f6f8fa;
        box-sizing: border-box;
    }


    /* Center the actual form */

    .container>form {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px 36px 36px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        box-sizing: border-box;
    }


    /* Center title with the form */

    .container>.title {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto 24px;
        text-align: left;
    }

    /* =========================================================
   PAGE TITLE
   ========================================================= */

    .title {
        margin: 0 0 28px;
        padding-bottom: 18px;
        text-align: left;
        color: var(--text);
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.3px;
        border-bottom: 1px solid var(--border);
    }

    .title::after {
        content: "";
        display: block;
        width: 38px;
        height: 3px;
        margin-top: 12px;
        background: var(--primary);
        border-radius: 2px;
    }


    /* =========================================================
   FORM GRID
   ========================================================= */

    .grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 22px;
        row-gap: 19px;
    }

    /* =========================================================
   INPUT GROUP
   ========================================================= */

    .input-group {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .input-group.full {
        grid-column: 1 / -1;
    }


    /* =========================================================
   LABELS
   ========================================================= */

    .input-group label {
        margin-bottom: 7px;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
    }

    .input-group label::after {
        content: "";
    }


    /* =========================================================
   INPUTS / SELECT / TEXTAREA
   ========================================================= */

    .input-group input,
    .input-group select,
    .input-group textarea {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 6px;
        background: #ffffff;
        color: #1f2937;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition:
            border-color 0.18s ease,
            box-shadow 0.18s ease,
            background 0.18s ease;
    }


    /* Normal input */

    .input-group input,
    .input-group select {
        height: 42px;
        padding: 0 12px;
    }


    /* Textarea */

    .input-group textarea {
        min-height: 105px;
        padding: 11px 12px;
        line-height: 1.55;
        resize: vertical;
    }


    /* =========================================================
   PLACEHOLDER
   ========================================================= */

    .input-group input::placeholder,
    .input-group textarea::placeholder {
        color: #a1aab5;
    }


    /* =========================================================
   HOVER
   ========================================================= */

    .input-group input:hover,
    .input-group select:hover,
    .input-group textarea:hover {
        border-color: var(--border-hover);
    }


    /* =========================================================
   FOCUS
   ========================================================= */

    .input-group input:focus,
    .input-group select:focus,
    .input-group textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 168, 132, 0.09);
        background: #ffffff;
    }


    /* =========================================================
   SELECT
   ========================================================= */

    .input-group select {
        cursor: pointer;
        appearance: auto;
    }


    /* =========================================================
   DATE INPUT
   ========================================================= */

    .input-group input[type="date"] {
        color: #374151;
        cursor: pointer;
    }


    /* =========================================================
   NUMBER INPUT
   ========================================================= */

    .input-group input[type="number"] {
        appearance: textfield;
    }

    .input-group input[type="number"]::-webkit-inner-spin-button,
    .input-group input[type="number"]::-webkit-outer-spin-button {
        opacity: 0.55;
    }


    /* =========================================================
   FILE INPUT
   ========================================================= */

    .input-group input[type="file"] {
        height: 42px;
        padding: 5px 8px;
        color: var(--text-secondary);
        cursor: pointer;
        background: #fafbfc;
    }

    .input-group input[type="file"]::file-selector-button {
        height: 30px;
        margin-right: 10px;
        padding: 0 11px;
        border: 1px solid #d7dee5;
        border-radius: 5px;
        background: #ffffff;
        color: #475569;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.18s ease;
    }

    .input-group input[type="file"]::file-selector-button:hover {
        background: #f1f5f7;
        border-color: #cbd5e1;
    }


    /* =========================================================
   ALERT / SUCCESS MESSAGE
   Works with your existing PHP messages
   ========================================================= */

    .container>form {
        position: relative;
    }


    /*
   If you change the PHP message divs to these classes,
   they will look polished:

   <div class="form-message success">...</div>
   <div class="form-message error">...</div>
*/

    .form-message {
        padding: 11px 13px;
        margin-bottom: 20px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .form-message.success {
        color: var(--success);
        background: var(--success-bg);
        border: 1px solid #bbf7d0;
    }

    .form-message.error {
        color: var(--danger);
        background: var(--danger-bg);
        border: 1px solid #fecaca;
    }


    /* =========================================================
   SUBMIT BUTTON
   ========================================================= */

    .add_medicine {
        width: 100%;
        height: 44px;
        margin-top: 27px;
        padding: 0 18px;
        border: 1px solid var(--primary);
        border-radius: 6px;
        background: var(--primary);
        color: #ffffff;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            box-shadow 0.18s ease,
            transform 0.15s ease;
    }

    .add_medicine:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
        box-shadow: 0 4px 12px rgba(0, 168, 132, 0.16);
        transform: translateY(-1px);
    }

    .add_medicine:active {
        transform: translateY(0);
        box-shadow: none;
    }


    /* =========================================================
   REQUIRED FIELD INDICATOR
   ========================================================= */

    .input-group input:required,
    .input-group select:required {
        background-color: #fff;
    }


    /* =========================================================
   DISABLED FIELD
   ========================================================= */

    .input-group input:disabled,
    .input-group select:disabled {
        background: #f1f5f7;

        color: #94a3b8;

        border-color: #e5e7eb;

        cursor: not-allowed;
    }

    /* manufacturer icon css */
    /* =========================================================
   MANUFACTURER SELECT
   ========================================================= */

    .manufacturer-select {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .manufacturer-select select {
        flex: 1;
        min-width: 0;
    }

    .add-manufacturer {
        width: 42px;
        height: 42px;
        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid var(--primary);
        border-radius: 6px;

        background: var(--primary);
        color: #ffffff;

        text-decoration: none;
        font-size: 24px;
        font-weight: 400;
        line-height: 1;

        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            transform 0.15s ease,
            box-shadow 0.18s ease;
    }

    .add-manufacturer:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
        box-shadow: 0 4px 12px rgba(0, 168, 132, 0.16);
        transform: translateY(-1px);
    }

    .add-manufacturer:active {
        transform: translateY(0);
        box-shadow: none;
    }

    /* =========================================================
   RESPONSIVE — TABLET
   ========================================================= */

    @media (max-width: 1100px) {

        .container {
            width: calc(100% - 245px);

            padding: 28px 25px;
        }

        .grid {
            column-gap: 18px;
        }
    }


    /* =========================================================
   RESPONSIVE — SMALL TABLET
   ========================================================= */

    @media (max-width: 850px) {

        .container {
            width: calc(100% - 245px);

            padding: 25px 20px;
        }

        .grid {
            grid-template-columns: 1fr;
        }

        .input-group.full {
            grid-column: auto;
        }
    }


    /* =========================================================
   RESPONSIVE — MOBILE
   ========================================================= */

    @media (max-width: 650px) {

        .container {
            width: 100%;
            margin-left: 0;
            padding: 22px 15px 30px;
        }

        .container>form {
            max-width: none;
            padding: 25px 20px 30px;
        }

        .container>.title {
            max-width: none;
        }

        .title {
            font-size: 20px;

            margin-bottom: 23px;
        }

        .grid {
            grid-template-columns: 1fr;

            row-gap: 16px;
        }

        .input-group.full {
            grid-column: auto;
        }

        .input-group input,
        .input-group select {
            height: 41px;
        }

        .add_medicine {
            margin-top: 23px;
        }
    }
</style>


<div class="container">

    <h2 class="title">Add Medicine</h2>


    <?php if ($message): ?>

        <div style="color:green;margin-bottom:20px;">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>


    <?php if ($error): ?>

        <div style="color:red;margin-bottom:20px;">
            <?= htmlspecialchars($error) ?>
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

                    <option value="">
                        Select Category
                    </option>

                    <?php while ($row = $categories->fetch_assoc()): ?>

                        <option value="<?= $row['id'] ?>" <?= old('category_id') == $row['id']
                              ? 'selected'
                              : '' ?>>
                            <?= htmlspecialchars($row['name']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>


            <!-- Dosage Form -->
            <div class="input-group">

                <label>Dosage Form</label>

                <select name="dosage_form_id" id="dosage_form" required>

                    <option value="">
                        Select Dosage Form
                    </option>

                    <?php while ($row = $dosageForms->fetch_assoc()): ?>

                        <option value="<?= $row['id'] ?>" <?= old('dosage_form_id') == $row['id']
                              ? 'selected'
                              : '' ?>>
                            <?= htmlspecialchars($row['name']) ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>


            <!-- Manufacturer -->
            <!-- Manufacturer -->
            <div class="input-group">
                <label>Manufacturer</label>

                <div class="manufacturer-select">
                    <select name="manufacturer_id" required>
                        <option value="">Select Manufacturer</option>

                        <?php while ($row = $manufacturers->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= old('manufacturer_id') == $row['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <a href="add_maufactuere.php" class="add-manufacturer" title="Add Manufacturer"
                        aria-label="Add Manufacturer">
                        +
                    </a>
                </div>
            </div>


            <!-- Batch Number -->
            <div class="input-group">

                <label>Batch Number</label>

                <input type="text" name="batch_number" value="<?= old('batch_number') ?>" placeholder="e.g. PCM2026001"
                    required>

            </div>


            <!-- Strength -->
            <div class="input-group">

                <label>Strength</label>

                <input type="text" name="strength" value="<?= old('strength') ?>" placeholder="500 mg">

            </div>


            <!-- Pack Size -->
            <div class="input-group">

                <label>Pack Size Per Strip</label>

                <input type="number" name="pack_size_per_strip" id="pack_size_per_strip" min="1"
                    value="<?= old('pack_size_per_strip') ?>">

            </div>


            <!-- Quantity -->
            <div class="input-group">

                <label>Quantity</label>

                <input type="number" name="quantity" min="0" value="<?= old('quantity', 0) ?>" required>

            </div>


            <!-- Minimum Stock -->
            <div class="input-group">

                <label>Minimum Stock Level</label>

                <input type="number" name="min_stock_level" min="0" value="<?= old('min_stock_level', 10) ?>" required>

            </div>

            <!-- Manufacturing Date -->
            <div class="input-group">
                <label>Manufacturing Date</label>
                <input type="date" name="mfg_date" value="<?= old('mfg_date') ?>" required>
            </div>

            <!-- Expiry Date -->
            <div class="input-group">

                <label>Expiry Date</label>

                <input type="date" name="expiry_date" value="<?= old('expiry_date') ?>" required>
            </div>

            <!-- Cost Price -->
            <div class="input-group">

                <label>Cost Price</label>

                <input type="number" step="0.01" min="0" name="cost_price" value="<?= old('cost_price') ?>" required>
            </div>

            <!-- Selling Price -->
            <div class="input-group">

                <label>Selling Price</label>

                <input type="number" step="0.01" min="0" name="selling_price" value="<?= old('selling_price') ?>"
                    required>
            </div>

            <!-- Medicine Image -->
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

        <button class="add_medicine" type="submit">
            Add Medicine
        </button>

    </form>

</div>



<!--  include 'partials/footer.php'; -->