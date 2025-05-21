<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch active users
$result = $conn->query("SELECT * FROM users WHERE status = 'active'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
</head>
<body>
    <h1>User Management</h1>
    <a href="add_user.php">➕ Add User</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th><th>Username</th><th>Role</th><th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
                <a href="edit_user.php?id=<?= $row['id'] ?>">✏️ Edit</a> |
                <a href="archive_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Deactivate this user?');">🗃️ Archive</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
