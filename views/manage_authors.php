<?php
// views/manage_authors.php
session_start();
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../classes/User.php';

if (!isset($_SESSION['userId']) || $_SESSION['userType'] !== 'Administrator') {
    header("Location: ../views/login.php");
    exit();
}

$userObj = new User($conn);
$message = "";

// Handle deletion of author
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $author = $userObj->getById($delId);
    if ($author && $author['UserType'] === 'Author') {
        if ($userObj->delete($delId)) {
            $message = "Author deleted successfully.";
        } else {
            $message = "Failed to delete author.";
        }
    } else {
        $message = "Invalid author or cannot delete this user.";
    }
}

// Handle add or update author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? null;
    $fullName = trim($_POST['Full_Name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone_Number']);
    $username = trim($_POST['User_Name']);
    $password = $_POST['Password'] ?? '';
    $address = trim($_POST['Address']);
    $profileImage = '';

    // Handle profile image upload
    if (isset($_FILES['profile_Image']) && $_FILES['profile_Image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $filename = basename($_FILES['profile_Image']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['profile_Image']['tmp_name'], $targetFile)) {
            $profileImage = 'uploads/' . $filename;
        }
    }

    $userData = [
        'Full_Name' => $fullName,
        'email' => $email,
        'phone_Number' => $phone,
        'User_Name' => $username,
        'UserType' => 'Author',
        'profile_Image' => $profileImage,
        'Address' => $address,
    ];

    if (!empty($password)) {
        $userData['Password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    if ($userId) {
        // Update author (username cannot be changed)
        unset($userData['User_Name']);
        if ($userObj->update($userId, $userData)) {
            $message = "Author updated successfully.";
        } else {
            $message = "Failed to update author.";
        }
    } else {
        // Create new author: username & password required
        if (empty($password) || empty($username)) {
            $message = "Username and Password are required for new authors.";
        } else {
            // Check if username already exists
            $existingUser = $userObj->getByUsername($username);
            if ($existingUser) {
                $message = "Username already exists. Please choose a different username.";
            } else {
                if ($userObj->create($userData)) {
                    $message = "Author added successfully.";
                } else {
                    $message = "Failed to add author.";
                }
            }
        }
    }
}

// Get all authors only
$authors = $userObj->getAllByType('Author');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Authors - Administrator</title>
    <link rel="stylesheet" href="../css/manage_authors.css">
</head>
<body>
<h2>Manage Authors</h2>

<?php if ($message) echo "<p class='message'>$message</p>"; ?>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="userId" id="userId" value="">

    <label>Full Name:</label><br>
    <input type="text" name="Full_Name" id="Full_Name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label>Phone Number:</label><br>
    <input type="text" name="phone_Number" id="phone_Number"><br><br>

    <label>Username:</label><br>
    <input type="text" name="User_Name" id="User_Name" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="Password" id="Password"><br><br>

    <label>Profile Image:</label><br>
    <input type="file" name="profile_Image" id="profile_Image" accept="image/*"><br><br>

    <label>Address:</label><br>
    <textarea name="Address" id="Address" rows="4" cols="50"></textarea><br><br>

    <button type="submit">Save Author</button>
    <button type="button" onclick="clearForm()">Clear</button>
</form>

<h3>Existing Authors</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
    <tr>
        <th>User ID</th><th>Full Name</th><th>Email</th><th>Username</th><th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $authors->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['userId']; ?></td>
            <td><?php echo htmlspecialchars($row['Full_Name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['User_Name']); ?></td>
            <td>
                <button class="edit-btn" onclick='editAuthor(<?php echo json_encode($row); ?>)'>Edit</button>
                <a class="delete-link" href="?delete=<?php echo $row['userId']; ?>" onclick="return confirm('Delete this author?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<script>
function editAuthor(author) {
    document.getElementById('userId').value = author.userId;
    document.getElementById('Full_Name').value = author.Full_Name;
    document.getElementById('email').value = author.email;
    document.getElementById('phone_Number').value = author.phone_Number;
    document.getElementById('User_Name').value = author.User_Name;
    document.getElementById('User_Name').disabled = true; // username can't be changed
    document.getElementById('Password').value = '';
    document.getElementById('Address').value = author.Address;
}

function clearForm() {
    document.getElementById('userId').value = '';
    document.getElementById('Full_Name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('phone_Number').value = '';
    document.getElementById('User_Name').value = '';
    document.getElementById('User_Name').disabled = false;
    document.getElementById('Password').value = '';
    document.getElementById('Address').value = '';
}
</script>

<br>
<a class="back-link" href="dashboard.php">Back to Dashboard</a>
</body>
</html>
