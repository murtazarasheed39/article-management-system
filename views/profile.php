<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['User_Name'])) {
    header("Location: login.php");
    exit;
}

// Example user info pulled from session (replace with DB query if needed)
$full_name = $_SESSION['Full_Name'] ?? "John Doe";
$email = $_SESSION['Email'] ?? "johndoe@example.com";
$phone = $_SESSION['Phone'] ?? "123-456-7890";
$address = $_SESSION['Address'] ?? "Nairobi, Kenya";
$user_type = $_SESSION['User_Type'] ?? "Author";
$profile_image = $_SESSION['Profile_Image'] ?? "../uploads/default.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="home.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="profile.php" class="active">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <div class="profile-card">
        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture">
        <h2><?php echo htmlspecialchars($full_name); ?></h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['User_Name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p><strong>User Type:</strong> <?php echo htmlspecialchars($user_type); ?></p>

        <a href="update_profile.php" class="btn">Edit Profile</a>
    </div>
</div>

</body>
</html>
