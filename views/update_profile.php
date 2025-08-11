<?php
// views/update_profile.php
session_start();
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../classes/User.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userObj = new User($conn);
$message = "";
$userId = $_SESSION['userId'];
$currentUser = $userObj->getById($userId);

if (!$currentUser) {
    header("Location: logout.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['Full_Name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone_Number']);
    $password = $_POST['Password'] ?? '';
    $address = trim($_POST['Address']);

    $profileImage = $currentUser['profile_Image'] ?? '';
    if (isset($_FILES['profile_Image']) && $_FILES['profile_Image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = basename($_FILES['profile_Image']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['profile_Image']['tmp_name'], $targetFile)) {
            $profileImage = 'uploads/' . $filename;
        }
    }

    $data = [
        'Full_Name' => $fullName,
        'email' => $email,
        'phone_Number' => $phone,
        'profile_Image' => $profileImage,
        'Address' => $address,
    ];

    if ($password) {
        $data['Password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    if ($userObj->update($userId, $data)) {
        $message = "Profile updated successfully.";
        $_SESSION['Full_Name'] = $fullName;
        $currentUser = $userObj->getById($userId);
    } else {
        $message = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="../css/update_profile.css">
</head>
<body>
<main>
    <h2>Update My Profile</h2>

    <?php if ($message): ?>
        <p class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" action="">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="Full_Name" value="<?php echo htmlspecialchars($currentUser['Full_Name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone_Number" value="<?php echo htmlspecialchars($currentUser['phone_Number']); ?>">

        <label for="password">New Password: <small>(leave blank to keep current)</small></label>
        <input type="password" id="password" name="Password">

        <label for="profile_image">Profile Image:</label>
        <input type="file" id="profile_image" name="profile_Image">

        <?php if (!empty($currentUser['profile_Image'])): ?>
            <p>Current Image:</p>
            <img src="<?php echo htmlspecialchars($currentUser['profile_Image']); ?>" alt="Profile Image" class="profile-img-preview">
        <?php endif; ?>

        <label for="address">Address:</label>
        <textarea id="address" name="Address" rows="4"><?php echo htmlspecialchars($currentUser['Address']); ?></textarea>

        <button type="submit">Update Profile</button>
    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</main>
</body>
</html>
