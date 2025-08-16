<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/User.php';

$userObj = new User($conn);

// Check if ID is passed
if (!isset($_GET['id'])) {
    die("Author ID is missing.");
}

$id = intval($_GET['id']);

// Try delete
if ($userObj->delete($id)) {
    // ✅ Redirect correctly to manage_authors.php in the same folder
    header("Location: ./manage_authors.php?msg=deleted");
    exit;
} else {
    echo "Error deleting author.";
}
?>
