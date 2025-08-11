<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../db/connection.php';

// Fetch last 6 articles ordered by created date desc
$sql = "SELECT a.*, u.Full_Name FROM articles a 
        JOIN users u ON a.authorId = u.userId
        WHERE article_display = 'yes' 
        ORDER BY article_created_date DESC LIMIT 6";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Articles</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="articles.php" class="active">Articles</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

    <aside class="sidebar">
        <h2>Categories</h2>
        <a href="#">Technology</a>
        <a href="#">Business</a>
        <a href="#">Education</a>
    </aside>

    <main class="content">
        <h1>Latest Articles</h1>

        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<article>";
                echo "<h2>" . htmlspecialchars($row['article_title']) . "</h2>";
                echo "<small>By " . htmlspecialchars($row['Full_Name']) . " on " . htmlspecialchars($row['article_created_date']) . "</small>";
                echo "<p>" . nl2br(htmlspecialchars($row['article_full_text'])) . "</p>";
                echo "</article><hr>";
            }
        } else {
            echo "<p>No articles to display.</p>";
        }
        ?>
    </main>

</div>

</body>
</html>
