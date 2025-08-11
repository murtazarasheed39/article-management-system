<?php
// views/manage_articles.php
session_start();
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../classes/Article.php';
require_once __DIR__ . '/../classes/User.php';

if (!isset($_SESSION['userId']) || $_SESSION['userType'] !== 'Administrator') {
    header("Location: login.php");
    exit();
}

$articleObj = new Article($conn);
$userObj = new User($conn);
$message = "";

// Delete article
if (isset($_GET['delete'])) {
    $articleId = (int)$_GET['delete'];
    if ($articleObj->delete($articleId)) {
        $message = "Article deleted successfully.";
    } else {
        $message = "Failed to delete article.";
    }
}

// Add or update article
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = $_POST['articleId'] ?? null;
    $authorId = (int)$_POST['authorId'];
    $title = trim($_POST['article_title']);
    $fullText = trim($_POST['article_full_text']);
    $display = $_POST['article_display'] === 'yes' ? 'yes' : 'no';
    $order = (int)$_POST['article_order'];

    $data = [
        'authorId' => $authorId,
        'article_title' => $title,
        'article_full_text' => $fullText,
        'article_display' => $display,
        'article_order' => $order,
    ];

    if ($articleId) {
        if ($articleObj->update($articleId, $data)) {
            $message = "Article updated successfully.";
        } else {
            $message = "Failed to update article.";
        }
    } else {
        if ($articleObj->create($data)) {
            $message = "Article added successfully.";
        } else {
            $message = "Failed to add article.";
        }
    }
}

// Get all articles with author names
$articles = $articleObj->getAll();

// Get all authors for dropdown
$authors = $userObj->getAllByType('Author');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Articles - Administrator</title>
    <link rel="stylesheet" href="../css/manage_articles.css" />
</head>
<body>
<h2>Manage Articles</h2>

<?php if ($message) echo "<p>$message</p>"; ?>

<form method="post">
    <input type="hidden" name="articleId" id="articleId" value="">

    <label for="authorId">Author:</label><br>
    <select name="authorId" id="authorId" required>
        <option value="">Select Author</option>
        <?php while ($author = $authors->fetch_assoc()): ?>
            <option value="<?php echo $author['userId']; ?>"><?php echo htmlspecialchars($author['Full_Name']); ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="article_title">Title:</label><br>
    <input type="text" name="article_title" id="article_title" required><br><br>

    <label for="article_full_text">Full Text:</label><br>
    <textarea name="article_full_text" id="article_full_text" rows="6" cols="50" required></textarea><br><br>

    <label for="article_display">Display (yes/no):</label><br>
    <select name="article_display" id="article_display" required>
        <option value="yes">Yes</option>
        <option value="no">No</option>
    </select><br><br>

    <label for="article_order">Order:</label><br>
    <input type="number" name="article_order" id="article_order" value="0" required><br><br>

    <button type="submit">Save Article</button>
    <button type="button" onclick="clearForm()">Clear</button>
</form>

<h3>All Articles</h3>
<table>
    <thead>
    <tr>
        <th>ID</th><th>Title</th><th>Author</th><th>Created Date</th><th>Display</th><th>Order</th><th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($article = $articles->fetch_assoc()): ?>
        <tr>
            <td><?php echo $article['articleId']; ?></td>
            <td><?php echo htmlspecialchars($article['article_title']); ?></td>
            <td><?php echo htmlspecialchars($article['Full_Name']); ?></td>
            <td><?php echo $article['article_created_date']; ?></td>
            <td><?php echo $article['article_display']; ?></td>
            <td><?php echo $article['article_order']; ?></td>
            <td>
                <button type="button" onclick='editArticle(<?php echo json_encode($article); ?>)'>Edit</button>
                <a href="?delete=<?php echo $article['articleId']; ?>" class="delete" onclick="return confirm('Delete this article?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<script>
function editArticle(article) {
    document.getElementById('articleId').value = article.articleId;
    document.getElementById('authorId').value = article.authorId;
    document.getElementById('article_title').value = article.article_title;
    document.getElementById('article_full_text').value = article.article_full_text;
    document.getElementById('article_display').value = article.article_display;
    document.getElementById('article_order').value = article.article_order;
}

function clearForm() {
    document.getElementById('articleId').value = '';
    document.getElementById('authorId').value = '';
    document.getElementById('article_title').value = '';
    document.getElementById('article_full_text').value = '';
    document.getElementById('article_display').value = 'yes';
    document.getElementById('article_order').value = 0;
}
</script>

<br>
<a href="dashboard.php" class="back-link">Back to Dashboard</a>
</body>
</html>
