<?php
// If you have a login system, you can add session checks here
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Article Management - Home</title>
    <link rel="stylesheet" href="../css/home.css">
    <script>
        // Sidebar toggle script
        function toggleSidebar() {
            document.querySelector(".sidebar").classList.toggle("collapsed");
            document.querySelector(".content").classList.toggle("expanded");
        }
    </script>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar">
    <div class="nav-left">
        <a href="home.php" class="logo">MySite</a>
    </div>
    <div class="nav-right">
        <a href="home.php" class="active">Home</a>
        <a href="articles.php">Articles</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    </div>
</nav>

<div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="#">Add Article</a></li>
            <li><a href="#">Manage Users</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
        <h3>Info</h3>
        <p>Welcome to the Article Management System. Use the menu to navigate.</p>
    </aside>

    <!-- Main Content -->
    <main class="content">
        <h1>Welcome!</h1>
        <p>This is your dashboard. Select an option from the menu or sidebar.</p>
    </main>
</div>

</body>
</html>
