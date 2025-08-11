<?php
// views/view_articles.php
session_start();
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../classes/Article.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$articleObj = new Article($conn);
$articles = $articleObj->getLastN(6);

$userFullName = htmlspecialchars($_SESSION['Full_Name'] ?? $_SESSION['username']);
$userType = $_SESSION['userType'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Articles</title>
    <link rel="stylesheet" href="../css/view_articles.css" />
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="view_articles.php" class="active">View Articles</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Welcome, <?php echo $userFullName; ?></h2>
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
</div>

<!-- Main content -->
<main>
    <h2>Last 6 Articles</h2>

    <?php if ($articles->num_rows > 0): ?>
        <?php while ($article = $articles->fetch_assoc()): ?>
            <article>
                <h3><?php echo htmlspecialchars($article['article_title']); ?></h3>
                <p><small>By <?php echo htmlspecialchars($article['Full_Name']); ?> on <?php echo htmlspecialchars($article['article_created_date']); ?></small></p>
                <p><?php
                    $text = strip_tags($article['article_full_text']);
                    echo nl2br(htmlspecialchars(strlen($text) > 400 ? substr($text,0,400).'...' : $text));
                ?></p>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No articles to display.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</main>

</body>
</html>
