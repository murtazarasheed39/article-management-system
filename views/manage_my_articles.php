<?php
// views/manage_my_articles.php
session_start();
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../classes/Article.php';

if (!isset($_SESSION['userId']) || $_SESSION['userType'] !== 'Author') {
    header("Location: login.php");
    exit();
}

$articleObj = new Article($conn);
$message = "";

$userId = $_SESSION['userId'];

// Delete article (only own)
if (isset($_GET['delete'])) {
    $articleId = (int)$_GET['delete'];
    $article = $articleObj->getById($articleId);
    if ($article && $article['authorId'] == $userId) {
        if ($articleObj->delete($articleId)) {
            $message = "Article deleted successfully.";
        } else {
            $message = "Failed to delete article.";
        }
    } else {
        $message = "Cannot delete article.";
    }
}

// Add or update article (only own)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = $_POST['articleId'] ?? null;
    $title = trim($_POST['article_title']);
    $fullText = trim($_POST['article_full_text']);
    $display = $_POST['article_display'] === 'yes' ? 'yes' : 'no';
    $order = (int)$_POST['article_order'];

    $data = [
        'authorId' => $userId,
        'article_title' => $title,
        'article_full_text' => $fullText,
        'article_display' => $display,
        'article_order' => $order,
    ];

    if ($articleId) {
        $article = $articleObj->getById($articleId);
        if ($article && $article['authorId'] == $userId) {
            if ($articleObj->update($articleId, $data)) {
                $message = "Article updated successfully.";
            } else {
                $message = "Failed to update article.";
            }
        } else {
            $message = "Unauthorized to update this article.";
        }
    } else {
        if ($articleObj->create($data)) {
            $message = "Article added successfully.";
        } else {
            $message = "Failed to add article.";
        }
    }
}

// Get all articles by this author
$articles = $articleObj->getByAuthor($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage My Articles - Author</title>
    <link rel="stylesheet" href="../css/manage_my_articles.css" />
</head>
<body>

<div class="container">

    <h2>Manage My Articles</h2>

    <?php if ($message): ?>
        <p class="<?php echo strpos($message, 'failed') !== false || strpos($message, 'Unauthorized') !== false || strpos($message, 'Cannot') !== false ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="post" novalidate>
        <input type="hidden" name="articleId" id="articleId" value="">

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

    <h3>My Articles</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Title</th><th>Created Date</th><th>Display</th><th>Order</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($article = $articles->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $article['articleId']; ?></td>
                    <td><?php echo htmlspecialchars($article['article_title']); ?></td>
                    <td><?php echo $article['article_created_date']; ?></td>
                    <td><?php echo $article['article_display']; ?></td>
                    <td><?php echo $article['article_order']; ?></td>
                    <td class="actions">
                        <button type="button" onclick='editArticle(<?php echo json_encode($article, JSON_HEX_TAG); ?>)'>Edit</button>
                        <a href="?delete=<?php echo $article['articleId']; ?>" class="delete" onclick="return confirm('Delete this article?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-link">Back to Dashboard</a>

</div>

<script>
function editArticle(article) {
    document.getElementById('articleId').value = article.articleId;
    document.getElementById('article_title').value = article.article_title;
    document.getElementById('article_full_text').value = article.article_full_text;
    document.getElementById('article_display').value = article.article_display;
    document.getElementById('article_order').value = article.article_order;
}

function clearForm() {
    document.getElementById('articleId').value = '';
    document.getElementById('article_title').value = '';
    document.getElementById('article_full_text').value = '';
    document.getElementById('article_display').value = 'yes';
    document.getElementById('article_order').value = 0;
}
</script>

</body>
</html>
