<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Set default date range to last 30 days
$start_date = date('Y-m-d', strtotime('-30 days'));
$end_date = date('Y-m-d');

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    // Validate dates from user input
    $start_date_input = $_GET['start_date'];
    $end_date_input = $_GET['end_date'];

    if (strtotime($start_date_input) && strtotime($end_date_input)) {
        $start_date = $start_date_input;
        $end_date = $end_date_input;
    }
}

// Prepare SQL with date filter
$sql = "SELECT * FROM sales WHERE sale_date BETWEEN ? AND ? ORDER BY sale_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
</head>
<body>
    <h1>Sales Report</h1>

    <form method="GET" action="reports.php">
        <label>Start Date:
            <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
        </label>
        <label>End Date:
            <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
        </label>
        <button type="submit">Filter</button>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0" style="margin-top:20px;">
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total (₱)</th>
            </tr>
            <?php
            $total = 0;
            while ($row = $result->fetch_assoc()):
                $total += $row['total_price'];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['sale_date']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= (int)$row['quantity'] ?></td>
                <td>₱<?= number_format($row['total_price'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
            <tr>
                <td colspan="3" align="right"><strong>Total Sales:</strong></td>
                <td><strong>₱<?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>
    <?php else: ?>
        <p>No sales found for the selected dates.</p>
    <?php endif; ?>
</body>
</html>
