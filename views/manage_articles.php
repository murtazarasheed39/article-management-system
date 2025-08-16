<?php
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
$msg = "";
$msgClass = "";

// Delete article
if (isset($_GET['delete'])) {
    $articleId = (int)$_GET['delete'];
    if ($articleObj->delete($articleId)) {
        $msg = "Article deleted successfully.";
        $msgClass = "success";
    } else {
        $msg = "Failed to delete article.";
        $msgClass = "error";
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
            $msg = "Article updated successfully.";
            $msgClass = "success";
        } else {
            $msg = "Failed to update article.";
            $msgClass = "error";
        }
    } else {
        if ($articleObj->create($data)) {
            $msg = "Article added successfully.";
            $msgClass = "success";
        } else {
            $msg = "Failed to add article.";
            $msgClass = "error";
        }
    }
}

// Get all articles with author names
$articles = $articleObj->getAll();
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
<main>
    <h2>Manage Articles</h2>

    <!-- Messages -->
    <?php if ($msg): ?>
        <p class="message <?= $msgClass ?>"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <!-- Article Form -->
    <form method="post">
        <input type="hidden" name="articleId" id="articleId" value="">

        <label for="authorId">Author:</label>
        <select name="authorId" id="authorId" required>
            <option value="">Select Author</option>
            <?php while ($author = $authors->fetch_assoc()): ?>
                <option value="<?= $author['userId'] ?>"><?= htmlspecialchars($author['Full_Name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="article_title">Title:</label>
        <input type="text" name="article_title" id="article_title" required>

        <label for="article_full_text">Full Text:</label>
        <textarea name="article_full_text" id="article_full_text" rows="6" required></textarea>

        <label for="article_display">Display (yes/no):</label>
        <select name="article_display" id="article_display" required>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>

        <label for="article_order">Order:</label>
        <input type="number" name="article_order" id="article_order" value="0" required>

        <button type="submit">Save Article</button>
        <button type="button" onclick="clearForm()">Clear</button>
    </form>

    <!-- Articles Table -->
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Created Date</th>
                <th>Display</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($articles && $articles->num_rows > 0): ?>
                <?php while ($article = $articles->fetch_assoc()): ?>
                    <tr>
                        <td><?= $article['articleId'] ?></td>
                        <td><?= htmlspecialchars($article['article_title']) ?></td>
                        <td><?= htmlspecialchars($article['Full_Name']) ?></td>
                        <td><?= $article['article_created_date'] ?></td>
                        <td><?= $article['article_display'] ?></td>
                        <td><?= $article['article_order'] ?></td>
                        <td>
                            <button type="button" class="edit-btn" onclick='editArticle(<?= json_encode($article) ?>)'>Edit</button>
                            <a href="?delete=<?= $article['articleId'] ?>" class="delete-link" onclick="return confirm('Delete this article?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="no-data">No articles found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="back-link">
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
</main>

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
</body>
</html>
