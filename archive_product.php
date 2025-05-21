<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'manager') {
    header("Location: login.php");
    exit();
}
include 'db.php';

$id = $_GET['id'];
$conn->query("UPDATE product SET status='archived' WHERE id=$id");
$result = $conn->query("SELECT * FROM products WHERE status = 'archived'");

header("Location: product.php");
exit();
?>
