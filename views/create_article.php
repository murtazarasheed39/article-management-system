<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../views/login.php");
    exit;
}
require_once("../db/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);
    $display = $_POST['display'];
    $authorId = $_SESSION['userId'];

    $stmt = $conn->prepare("INSERT INTO articles (authorId, article_title, article_full_text, article_created_date, article_last_update, article_display) VALUES (?, ?, ?, NOW(), NOW(), ?)");
    $stmt->bind_param("isss", $authorId, $title, $content, $display);

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
    <title>Create Article</title>
</head>
<body>
    <h1>Create Article</h1>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="6" cols="50" required></textarea><br><br>

        <label>Display:</label>
        <select name="display">
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select><br><br>

        <button type="submit">Save</button>
    </form>
    <br>
    <a href="articles.php">Back</a>
</body>
</html>
