<?php
$title = "Inventory";
include 'partials/header.php';
require_once __DIR__ . '/../../config/conn.php';

$search = "";

$sql = "
SELECT
    m.id,
    m.generic_name,
    m.brand_name,
    c.name AS category_name,
    b.batch_number,
    man.name AS manufacturer_name,
    m.cost_price,
    m.selling_price,
    m.quantity,
    m.min_stock_level,
    m.expiry_date,
    m.status

FROM medicines m

LEFT JOIN categories c
    ON m.category_id = c.id

LEFT JOIN batches b
    ON m.batch_id = b.id

LEFT JOIN manufacturers man
    ON m.manufacturer_id = man.id
";

if (!empty($_GET['search'])) {

    $search = trim($_GET['search']);

    $sql .= "
    WHERE
        m.generic_name LIKE ?
        OR m.brand_name LIKE ?
        OR man.name LIKE ?
        OR b.batch_number LIKE ?
    ";
}

$sql .= " ORDER BY m.brand_name ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($search)) {

    $searchTerm = "%{$search}%";

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!--inventory css  -->
<link rel="stylesheet" href="../../config/inventory.css">

<div class="container">

    <div class="top">

        <h2>Medicine Inventory</h2>

        <!-- Search  -->
        <div class="search-box">
            <form method="GET" action="">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Search by generic, brand, manufacturer or batch..."
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </form>
        </div>

        <a href="add_medicine" class="btn">

            <i class="fa-solid fa-plus"></i>

            Add Medicine

        </a>

    </div>

    <table>

        <thead>

            <tr>

                <th>ID</th>

                <th>Medicine</th>

                <th>Category</th>

                <th>Batch</th>

                <th>Manufacturer</th>

                <th>Purchase</th>

                <th>Selling</th>

                <th>Stock</th>

                <th>Expiry</th>

                <th>Status</th>

                <th>Action</th>

            </tr>

        </thead>

        <tbody>

            <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>

                <tr>

                    <td><?= $row['id']; ?></td>

                    <td>
                        <strong><?= htmlspecialchars($row['brand_name']); ?></strong><br>
                        <small><?= htmlspecialchars($row['generic_name']); ?></small>
                    </td>

                    <td><?= htmlspecialchars($row['category_name']); ?></td>

                    <td><?= htmlspecialchars($row['batch_number']); ?></td>

                    <td><?= htmlspecialchars($row['manufacturer_name']); ?></td>

                    <td>Rs. <?= number_format($row['cost_price'], 2); ?></td>

                    <td>Rs. <?= number_format($row['selling_price'], 2); ?></td>

                    <td><?= $row['quantity']; ?></td>

                    <td>
                        <?= !empty($row['expiry_date']) ? date("d M Y", strtotime($row['expiry_date'])) : '-'; ?>
                    </td>

                    <td>

                        <?php

                        if (!empty($row['expiry_date']) && strtotime($row['expiry_date']) < time()) {

                            echo "<span class='expired'>Expired</span>";

                        } elseif ($row['quantity'] <= $row['min_stock_level']) {

                            echo "<span class='low'>Low Stock</span>";

                        } else {

                            echo "<span class='available'>Available</span>";

                        }

                        ?>

                    </td>

                    <td>

                        <a href="edit_medicine.php?id=<?= $row['id']; ?>" class="edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <a href="delete_medicine.php?id=<?= $row['id']; ?>" class="delete"
                            onclick="return confirm('Delete this medicine?')">

                            <i class="fa-solid fa-trash"></i>

                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="11" style="text-align:center;padding:25px;">
        No medicine found.
    </td>
</tr>

<?php endif; ?>

        </tbody>

    </table>

</div>






<?php
include 'partials/footer.php';
?>