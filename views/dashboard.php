<?php
// views/dashboard.php
session_start();
require_once __DIR__ . '/../db/connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$fullName = htmlspecialchars($_SESSION['Full_Name']);
$userType = htmlspecialchars($_SESSION['userType']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css" />
</head>
<body>

<div class="navbar">
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="view_articles.php">View Articles</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

    <aside class="sidebar">
        <h2>Welcome, <?php echo $fullName; ?></h2>
        <nav>
            <?php
            if ($userType === 'Super_User') {
                echo '<a href="update_profile.php">Update My Profile</a>';
                echo '<a href="manage_users.php">Manage Other Users</a>';
                echo '<a href="view_articles.php">View Articles</a>';
                echo '<a href="logout.php">Logout</a>';
            } elseif ($userType === 'Administrator') {
                echo '<a href="update_profile.php">Update My Profile</a>';
                echo '<a href="manage_authors.php">Manage Authors</a>';
                echo '<a href="manage_articles.php">Manage Articles</a>';
                echo '<a href="view_articles.php">View Articles</a>';
                echo '<a href="logout.php">Logout</a>';
            } elseif ($userType === 'Author') {
                echo '<a href="update_profile.php">Update My Profile</a>';
                echo '<a href="manage_my_articles.php">Manage My Articles</a>';
                echo '<a href="view_articles.php">View Articles</a>';
                echo '<a href="logout.php">Logout</a>';
            }
            ?>
        </nav>
    </aside>

    <main class="content">
        <h1>Dashboard</h1>
        <p>Welcome back, <strong><?php echo $fullName; ?></strong>!</p>
        <p>Your user type is <strong><?php echo $userType; ?></strong>.</p>
        <p>Use the sidebar to navigate through your options.</p>
    </main>

</div>

</body>
</html>
