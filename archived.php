<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "UPDATE products SET status = 'archived' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: products.php?archived=1");
        exit();
    } else {
        echo "Error archiving product: " . $conn->error;
    }
} else {
    echo "No product ID provided.";
}
?>
