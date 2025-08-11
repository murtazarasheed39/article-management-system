<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../views/login.php");
    exit;
}
require_once("../db/connection.php");

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if (!$article) {
    die("Article not found");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);
    $display = $_POST['display'];

    $stmt = $conn->prepare("UPDATE articles SET article_title=?, article_full_text=?, article_last_update=NOW(), article_display=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $content, $display, $id);

    if ($stmt->execute()) {
        header("Location: articles.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Article</title>
</head>
<body>
    <h1>Edit Article</h1>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($article['article_title']); ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="6" cols="50" required><?php echo htmlspecialchars($article['article_full_text']); ?></textarea><br><br>

        <label>Display:</label>
        <select name="display">
            <option value="yes" <?php if ($article['article_display'] == "yes") echo "selected"; ?>>Yes</option>
            <option value="no" <?php if ($article['article_display'] == "no") echo "selected"; ?>>No</option>
        </select><br><br>

        <button type="submit">Update</button>
    </form>
    <br>
    <a href="articles.php">Back</a>
</body>
</html>
