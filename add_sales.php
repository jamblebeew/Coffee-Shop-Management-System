<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}
include 'db.php';

// Fetch active products for dropdown
$productResult = $conn->query("SELECT id, name, price FROM products WHERE status = 'active'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Get product price
    $priceResult = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $priceResult->bind_param("i", $product_id);
    $priceResult->execute();
    $priceResult->bind_result($price);
    $priceResult->fetch();
    $priceResult->close();

    $total = $price * $quantity;
    $sale_date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity, total, sale_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iids", $product_id, $quantity, $total, $sale_date);

    if ($stmt->execute()) {
        // Redirect back to sales report after adding
        header("Location: sales.php");
        exit;
    } else {
        echo "Error adding sale: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Sale</title></head>
<body>
<h1>Add Sale</h1>

<form method="POST" action="">
    <label for="product_id">Product:</label>
    <select name="product_id" id="product_id" required>
        <option value="">-- Select Product --</option>
        <?php while ($product = $productResult->fetch_assoc()): ?>
            <option value="<?= $product['id'] ?>">
                <?= htmlspecialchars($product['name']) ?> (₱<?= number_format($product['price'], 2) ?>)
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" min="1" required><br><br>

    <button type="submit">Add Sale</button>
</form>

<a href="sales.php">⬅ Back to Sales Report</a>
</body>
</html>
