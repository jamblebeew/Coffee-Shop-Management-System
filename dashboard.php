<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Coffee Shop</title>
</head>
<body>
    <h1>Welcome to the Coffee Shop Dashboard</h1>
    <p>Hello, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
    <p>Your role: <strong><?php echo htmlspecialchars($role); ?></strong></p>

    <h2>Menu</h2>
    <ul>
        <?php if ($role === 'manager'): ?>
            <li><a href="products.php">Manage Products</a></li>
            <li><a href="users.php">Manage Users</a></li>
            <li><a href="reports.php">View Reports</a></li>
        <?php elseif ($role === 'staff'): ?>
            <li><a href="orders.php">View Orders</a></li>
            <li><a href="inventory.php">Check Inventory</a></li>
        <?php endif; ?>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
