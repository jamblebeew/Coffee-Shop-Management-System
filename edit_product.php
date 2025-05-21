<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (!isset($_GET['id'])) {
    echo "No product ID provided.";
    exit();
}

$id = intval($_GET['id']);

// Fetch current product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Image handling
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir);
        }
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $image = $product['image']; // keep existing
    }

    $update = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    $update->bind_param("sdsi", $name, $price, $image, $id);

    if ($update->execute()) {
        header("Location: products.php");
        exit();
    } else {
        echo "Failed to update product.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>

        <label>Price (₱):</label><br>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br><br>

        <label>Current Image:</label><br>
        <?php if ($product['image']): ?>
            <img src="uploads/<?= htmlspecialchars($product['image']) ?>" width="100"><br>
        <?php endif; ?>

        <label>Change Image:</label><br>
        <input type="file" name="image"><br><br>

        <button type="submit">Update Product</button>
    </form>
    <br>
    <a href="products.php">⬅ Back to Product List</a>
</body>
</html>
