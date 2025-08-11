<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../views/login.php");
    exit;
}
require_once("../db/connection.php");

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: articles.php");
exit;
?>
