<?php
$title = "| Inventory";
include 'partials/header.php';
include '../../config/conn.php';


$query = "
SELECT
    m.medicine_id,
    m.medicine_name,
    c.category_name,
    m.batch_no,
    m.manufacturer,
    m.purchase_price,
    m.selling_price,
    m.quantity,
    m.minimum_stock,
    m.expiry_date
FROM medicines m
LEFT JOIN categories c
ON m.category_id = c.category_id
ORDER BY m.medicine_name ASC
";

$result = mysqli_query($conn, $query);

?>

<!--inventory css  -->
<link rel="stylesheet" href="inventory.css">

<div class="container">

    <div class="top">

        <h2>Medicine Inventory</h2>

        <!-- Search  -->
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search medicines...">
        </div>

        <a href="add_medicine.php" class="btn">

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

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td><?= $row['medicine_id']; ?></td>

                    <td><?= $row['medicine_name']; ?></td>

                    <td><?= $row['category_name']; ?></td>

                    <td><?= $row['batch_no']; ?></td>

                    <td><?= $row['manufacturer']; ?></td>

                    <td>Rs. <?= number_format($row['purchase_price'], 2); ?></td>

                    <td>Rs. <?= number_format($row['selling_price'], 2); ?></td>

                    <td><?= $row['quantity']; ?></td>

                    <td><?= date("d M Y", strtotime($row['expiry_date'])); ?></td>

                    <td>

                        <?php

                        if (strtotime($row['expiry_date']) < time()) {

                            echo "<span class='expired'>Expired</span>";

                        } elseif ($row['quantity'] <= $row['minimum_stock']) {

                            echo "<span class='low'>Low Stock</span>";

                        } else {

                            echo "<span class='available'>Available</span>";

                        }

                        ?>

                    </td>

                    <td>

                        <a href="edit_medicine.php?id=<?= $row['medicine_id']; ?>" class="edit">

                            <i class="fa-solid fa-pen"></i>

                        </a>

                        <a href="delete_medicine.php?id=<?= $row['medicine_id']; ?>" class="delete"
                            onclick="return confirm('Delete this medicine?')">

                            <i class="fa-solid fa-trash"></i>

                        </a>

                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

</div>






<?php
include 'partials/footer.php';
?>