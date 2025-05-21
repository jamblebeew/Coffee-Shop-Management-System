<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$query = "SELECT sales.id, products.name AS product_name, sales.quantity, sales.total, sales.sale_date
          FROM sales
          INNER JOIN products ON sales.product_id = products.id
          ORDER BY sales.sale_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head><title>Sales Report</title></head>
<body>
<h1>Sales Report</h1>
<a href="add_sales.php">➕ Add Sale</a>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th><th>Product</th><th>Quantity</th><th>Total</th><th>Date</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>₱<?= number_format($row['total'], 2) ?></td>
                <td><?= $row['sale_date'] ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">No sales found.</td></tr>
    <?php endif; ?>
</table>

<a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
