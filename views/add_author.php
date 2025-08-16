<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/User.php';

$userObj = new User($conn);

// Handle form submission
$msg = "";
$msgClass = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'Full_Name'    => $_POST['Full_Name'],
        'email'        => $_POST['email'],
        'phone_Number' => $_POST['phone_Number'],
        'User_Name'    => $_POST['User_Name'],
        'Password'     => password_hash($_POST['Password'], PASSWORD_BCRYPT),
        'Address'      => $_POST['Address'],
        'UserType'     => 'Author'  // Must match DB column exactly
    ];

    if ($userObj->create($data)) {
        $msg = "Author added successfully.";
        $msgClass = "success";
    } else {
        $msg = "Error adding author.";
        $msgClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Author</title>
    <link rel="stylesheet" href="../css/update_profile.css"> <!-- Same styling as dashboard -->
</head>
<body>
<main>
    <h2>Add New Author</h2>

    <!-- Messages -->
    <?php if (!empty($msg)): ?>
        <p class="message <?= $msgClass ?>">
            <?= htmlspecialchars($msg) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="Full_Name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Phone Number:</label>
        <input type="text" name="phone_Number">

        <label>Username:</label>
        <input type="text" name="User_Name" required>

        <label>Password:</label>
        <input type="password" name="Password" required>

        <label>Address:</label>
        <textarea name="Address"></textarea>

        <button type="submit">Add Author</button>
    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</main>
</body>
</html>
