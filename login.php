<?php
include 'db.php';
// your code here

session_start();

// Dummy users (you can replace with database later)
$users = [
    ['username' => 'manager1', 'password' => 'pass123', 'role' => 'manager'],
    ['username' => 'staff1', 'password' => 'pass123', 'role' => 'staff'],
];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $foundUser = null;

    // Match user
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser) {
        $_SESSION['username'] = $foundUser['username'];
        $_SESSION['role'] = $foundUser['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Coffee Shop System</title>
</head>
<body>
    <h1>Login to Coffee Shop System</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
