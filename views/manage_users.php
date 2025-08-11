<?php
// views/manage_users.php
session_start();
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../classes/User.php';

if (!isset($_SESSION['userId']) || $_SESSION['userType'] !== 'Super_User') {
    header("Location: login.php");
    exit();
}

$userObj = new User($conn);
$message = "";

// Delete user (not Super_User)
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $userToDelete = $userObj->getById($delId);
    if ($userToDelete && $userToDelete['UserType'] !== 'Super_User') {
        if ($userObj->delete($delId)) {
            $message = "User deleted successfully.";
        } else {
            $message = "Failed to delete user.";
        }
    } else {
        $message = "Cannot delete Super_User or user not found.";
    }
}

// Add or update user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? null;
    $fullName = trim($_POST['Full_Name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone_Number']);
    $username = trim($_POST['User_Name']);
    $password = $_POST['Password'] ?? '';
    $userType = $_POST['UserType'] ?? 'Administrator'; // default
    $profileImage = trim($_POST['profile_Image']);
    $address = trim($_POST['Address']);

    // Validate UserType not Super_User to prevent creation of new Super_User
    if ($userType === 'Super_User') {
        $message = "Cannot create or modify Super_User via this interface.";
    } else {
        $hashedPassword = '';
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }

        $data = [
            'Full_Name' => $fullName,
            'email' => $email,
            'phone_Number' => $phone,
            'User_Name' => $username,
            'Password' => $hashedPassword,
            'UserType' => $userType,
            'profile_Image' => $profileImage,
            'Address' => $address,
        ];

        if ($userId) {
            // Update user (username cannot be changed)
            $existingUser = $userObj->getById($userId);
            if ($existingUser && $existingUser['UserType'] !== 'Super_User') {
                if (!$hashedPassword) {
                    unset($data['Password']);
                }
                if ($userObj->update($userId, $data)) {
                    $message = "User updated successfully.";
                } else {
                    $message = "Failed to update user.";
                }
            } else {
                $message = "Cannot update Super_User or user not found.";
            }
        } else {
            // Add new user (username unique)
            $checkUser = $userObj->getByUsername($username);
            if ($checkUser) {
                $message = "Username already exists.";
            } else {
                if (!$password) {
                    $message = "Password is required for new user.";
                } else {
                    $data['Password'] = $hashedPassword;
                    if ($userObj->create($data)) {
                        $message = "User added successfully.";
                    } else {
                        $message = "Failed to add user.";
                    }
                }
            }
        }
    }
}

// Fetch all users except Super_User
$users = $userObj->getAllExceptSuper();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Super User</title>
    <link rel="stylesheet" href="../css/manage_users.css">
</head>
<body>
<div class="container">
    <h2>Manage Other Users</h2>

    <?php if ($message): ?>
        <p class="<?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="userId" id="userId" value="">

        <label>Full Name:</label>
        <input type="text" name="Full_Name" id="Full_Name" required>

        <label>Email:</label>
        <input type="email" name="email" id="email" required>

        <label>Phone Number:</label>
        <input type="text" name="phone_Number" id="phone_Number">

        <label>Username (cannot change once created):</label>
        <input type="text" name="User_Name" id="User_Name" required>

        <label>Password (leave blank to keep current):</label>
        <input type="password" name="Password" id="Password">

        <label>User Type:</label>
        <select name="UserType" id="UserType">
            <option value="Administrator">Administrator</option>
            <option value="Author">Author</option>
        </select>

        <label>Profile Image URL:</label>
        <input type="text" name="profile_Image" id="profile_Image">

        <label>Address:</label>
        <textarea name="Address" id="Address" rows="3"></textarea>

        <button type="submit">Save User</button>
        <button type="button" onclick="clearForm()">Clear</button>
    </form>

    <h3>Existing Users</h3>
    <table>
        <thead>
        <tr>
            <th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Username</th><th>User Type</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['userId']; ?></td>
                <td><?php echo htmlspecialchars($user['Full_Name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['phone_Number']); ?></td>
                <td><?php echo htmlspecialchars($user['User_Name']); ?></td>
                <td><?php echo htmlspecialchars($user['UserType']); ?></td>
                <td class="actions">
                    <button onclick='editUser(<?php echo json_encode($user); ?>)'>Edit</button>
                    <a href="?delete=<?php echo $user['userId']; ?>" class="delete" onclick="return confirm('Delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function editUser(user) {
    document.getElementById('userId').value = user.userId;
    document.getElementById('Full_Name').value = user.Full_Name;
    document.getElementById('email').value = user.email;
    document.getElementById('phone_Number').value = user.phone_Number;
    document.getElementById('User_Name').value = user.User_Name;
    document.getElementById('User_Name').readOnly = true;
    document.getElementById('Password').value = '';
    document.getElementById('UserType').value = user.UserType;
    document.getElementById('profile_Image').value = user.profile_Image;
    document.getElementById('Address').value = user.Address;
}

function clearForm() {
    document.getElementById('userId').value = '';
    document.getElementById('Full_Name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('phone_Number').value = '';
    document.getElementById('User_Name').value = '';
    document.getElementById('User_Name').readOnly = false;
    document.getElementById('Password').value = '';
    document.getElementById('UserType').value = 'Administrator';
    document.getElementById('profile_Image').value = '';
    document.getElementById('Address').value = '';
}
</script>

<br>
<a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</body>
</html>
