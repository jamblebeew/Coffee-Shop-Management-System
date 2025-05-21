<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Image Upload
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    // Create uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO products (name, price, image, status) VALUES (?, ?, ?, 'active')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sds", $name, $price, $image);

        if ($stmt->execute()) {
            echo "✅ Product added successfully.";
            header("Location: products.php");
            exit();
        } else {
            echo "❌ Error: " . $stmt->error;
        }
    } else {
        echo "❌ Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>
    <h2>Add New Product</h2>
    <form method="POST" enctype="multipart/form-data">
        Name: <input type="text" name="name" required><br><br>
        Price: <input type="number" name="price" step="0.01" required><br><br>
        Image: <input type="file" name="image" accept="image/*" required><br><br>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>
