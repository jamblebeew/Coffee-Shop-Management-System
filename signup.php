<?php
session_start();

$error = "";
$success = "";

// Path to the file where users will be saved
$userfile = "users.txt";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists in users.txt
        if (file_exists($userfile)) {
            $users = file($userfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($users as $user) {
                list($saved_user, $saved_pass_hash) = explode(":", $user);
                if ($saved_user === $username) {
                    $error = "Username already taken.";
                    break;
                }
            }
        }

        if (!$error) {
            // Save new user to file (store password hashed)
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            file_put_contents($userfile, "$username:$password_hash\n", FILE_APPEND);

            $success = "Account created successfully! You can now <a href='login.php'>login</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - My Website</title>
</head>
<body>
    <h2>Sign Up</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php else: ?>
    <form method="POST" action="signup.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <input type="submit" value="Sign Up">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
    <?php endif; ?>
</body>
</html>
