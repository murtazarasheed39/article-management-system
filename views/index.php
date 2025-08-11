<?php
// filepath: article_management\src\views\index.php
session_start();
if (isset($_SESSION['userId'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
</head>
<body>
    <h2>Sign In</h2>
    <form action="signin.php" method="post">
        <label>Username:</label>
        <input type="text" name="User_Name" required><br>
        <label>Password:</label>
        <input type="password" name="Password" required><br>
        <button type="submit">Sign In</button>
    </form>
    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
</body>
</html>