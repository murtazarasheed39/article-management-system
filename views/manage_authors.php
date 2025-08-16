<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/User.php';

// Initialize User class with connection
$userObj = new User($conn);

// Handle messages
$msg = "";
$msgClass = "";
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case "deleted":
            $msg = "Author deleted successfully.";
            $msgClass = "success";
            break;
        case "updated":
            $msg = "Author updated successfully.";
            $msgClass = "success";
            break;
        case "error":
            $msg = "Something went wrong. Please try again.";
            $msgClass = "error";
            break;
    }
}

// Fetch all authors
$authors = $userObj->getAllByType('Author');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Authors</title>
    <link rel="stylesheet" href="../css/manage_authors.css">
</head>
<body>
    <main>
        <h2>Manage Authors</h2>

        <!-- Messages -->
        <?php if (!empty($msg)): ?>
            <p class="message <?= $msgClass ?>">
                <?= htmlspecialchars($msg) ?>
            </p>
        <?php endif; ?>

        <!-- Add Author button -->
        <a href="add_author.php" class="add-btn">+ Add New Author</a>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($authors && $authors->num_rows > 0): ?>
                    <?php while($row = $authors->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['userId'] ?></td>
                            <td><?= $row['Full_Name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['phone_Number'] ?></td>
                            <td><?= $row['User_Name'] ?></td>
                            <td>
                                <a href="edit_author.php?id=<?= $row['userId'] ?>" class="edit-btn">Edit</a>
                                <a href="delete_author.php?id=<?= $row['userId'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this author?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-data">No authors found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Back to Dashboard -->
        <div class="back-link">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </main>
</body>
</html>
