<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../db/connection.php';

$userId = $_SESSION['userId'];

// Fetch user data
$sql = "SELECT * FROM users WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/profile.css">
    
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="articles.php">Articles</a>
    <a href="profile.php" class="active">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

    <aside class="sidebar">
        <h2>Profile Options</h2>
        <a href="update_profile.php">Update Profile</a>
        <a href="change_password.php">Change Password</a>
    </aside>

    <main class="content">
        <h1>Update Profile</h1>

        <form action="update_profile_process.php" method="post" enctype="multipart/form-data">
            <label>Full Name:</label><br>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['Full_Name']); ?>" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

            <label>Phone Number:</label><br>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_Number']); ?>"><br><br>

            <label>Address:</label><br>
            <textarea name="address"><?php echo htmlspecialchars($user['Address']); ?></textarea><br><br>

            <label>Profile Image:</label><br>
            <input type="file" name="profile_image"><br><br>

            <input type="submit" value="Update Profile">
        </form>
    </main>

</div>

</body>
</html>
