<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}
include 'db.php';

$sql = "SELECT * FROM products WHERE status = 'active'";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: center;
        }
        img {
            max-width: 80px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Product Management</h1>
    <a href="add_product.php">➕ Add Product</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td>
                <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>₱<?= number_format($row['price'], 2) ?></td>
            <td>
                <a href="edit_product.php?id=<?= $row['id'] ?>">✏️ Edit</a> |
                <a href="archive_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Archive this product?');">🗃️ Archive</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
