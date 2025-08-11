<?php
session_start();
if (isset($_SESSION['User_Name'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../css/register.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <a href="#">Help</a>
    </aside>

    <main class="content">
        <h1>Register</h1>
        <?php
        if (isset($_GET['error'])) {
            echo "<p style='color:red'>" . htmlspecialchars($_GET['error']) . "</p>";
        }
        if (isset($_GET['success'])) {
            echo "<p style='color:green'>" . htmlspecialchars($_GET['success']) . "</p>";
        }
        ?>
        <form method="post" action="../controllers/register_process.php" enctype="multipart/form-data">
            <label>Full Name:</label><br>
            <input type="text" name="full_name" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Phone Number:</label><br>
            <input type="text" name="phone_number" required><br><br>

            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Address:</label><br>
            <textarea name="address" rows="3"></textarea><br><br>

            <label>Profile Image:</label><br>
            <input type="file" name="profile_image" accept="image/*"><br><br>

            <label>User Type:</label><br>
            <select name="user_type" required>
                <option value="Author">Author</option>
                <option value="Administrator">Administrator</option>
                <option value="Super_User">Super_User</option>
            </select><br><br>

            <input type="submit" value="Register">
        </form>
    </main>

</div>

</body>
</html>
