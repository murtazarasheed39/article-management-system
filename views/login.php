<?php
session_start();
if (isset($_SESSION['userId'])) {
    // Redirect logged-in users to dashboard
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<div class="navbar">
    <a href="login.php" <?php if(basename($_SERVER['PHP_SELF']) == 'login.php') echo 'class="active"'; ?>>Login</a>
    <a href="register.php" <?php if(basename($_SERVER['PHP_SELF']) == 'register.php') echo 'class="active"'; ?>>Register</a>
</div>

<div class="container">

    <aside class="sidebar">
        <h2>Info</h2>
        <a href="#">About</a>
        <a href="#">Support</a>
        <a href="#">Help</a>
    </aside>

    <main class="content">
        <h1>Login</h1>
        <form method="post" action="../controllers/login_process.php">
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br><br>

            <input type="submit" value="Login">
        </form>
    </main>

</div>

</body>
</html>
