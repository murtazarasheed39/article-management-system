<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/User.php';

$userObj = new User($conn);

// Check if ID is passed
if (!isset($_GET['id'])) {
    die("Author ID is missing.");
}

$id = intval($_GET['id']);
$author = $userObj->getById($id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'Full_Name'    => $_POST['Full_Name'],
        'email'        => $_POST['email'],
        'phone_Number' => $_POST['phone_Number'],
        'Password'     => !empty($_POST['Password']) ? password_hash($_POST['Password'], PASSWORD_BCRYPT) : "",
        'Address'      => $_POST['Address']
    ];

    if ($userObj->update($id, $data)) {
        header("Location: manage_authors.php?msg=updated");
        exit;
    } else {
        $msg = "Error updating author.";
        $msgClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Author</title>
    <link rel="stylesheet" href="../css/edit_author.css">
</head>
<body>
    <main>
        <h2>Edit Author</h2>

        <!-- Display message if exists -->
        <?php if (!empty($msg)): ?>
            <p class="message <?= $msgClass ?>"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <?php if ($author): ?>
        <form method="POST">
            <label>Full Name:</label>
            <input type="text" name="Full_Name" value="<?= htmlspecialchars($author['Full_Name']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($author['email']) ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone_Number" value="<?= htmlspecialchars($author['phone_Number']) ?>">

            <label>Address:</label>
            <textarea name="Address"><?= htmlspecialchars($author['Address']) ?></textarea>

            <label>Password (leave blank to keep same):</label>
            <input type="password" name="Password">

            <button type="submit">Update Author</button>
        </form>
        <?php else: ?>
            <p class="message error">Author not found.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="manage_authors.php">← Back to Manage Authors</a>
        </div>
    </main>
</body>
</html>
