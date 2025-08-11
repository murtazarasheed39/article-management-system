<?php
// edit_user.php
session_start();
require_once "connection.php";
if (!isset($_SESSION['userId']) || $_SESSION['UserType'] !== 'Super_User') {
    header("Location: index.php");
    exit();
}

$userObj = new User($mysqli);

if (!isset($_GET['id'])) {
    echo "No user ID.";
    exit();
}

$id = (int)$_GET['id'];
$u = $userObj->getById($id);
if (!$u) {
    echo "User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'Full_Name' => $_POST['Full_Name'],
        'email' => $_POST['email'],
        'phone_Number' => $_POST['phone_Number'],
        'profile_Image' => $_POST['profile_Image'],
        'Address' => $_POST['Address'],
    ];
    if (!empty($_POST['Password'])) {
        $data['Password'] = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    } else {
        $data['Password'] = "";
    }

    if ($userObj->update($id, $data)) {
        echo "<p style='color:green'>Updated</p>";
        $u = $userObj->getById($id);
    } else {
        echo "<p style='color:red'>Update failed.</p>";
    }
}
?>

<h3>Edit User (ID <?php echo $id; ?>)</h3>
<form method="post">
    Username (fixed): <b><?php echo htmlspecialchars($u['User_Name']); ?></b><br><br>
    Full Name: <input type="text" name="Full_Name" value="<?php echo htmlspecialchars($u['Full_Name']); ?>"><br>
    Email: <input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>"><br>
    Phone: <input type="text" name="phone_Number" value="<?php echo htmlspecialchars($u['phone_Number']); ?>"><br>
    New Password (leave blank to keep): <input type="password" name="Password"><br>
    Profile Image URL: <input type="text" name="profile_Image" value="<?php echo htmlspecialchars($u['profile_Image']); ?>"><br>
    Address: <textarea name="Address"><?php echo htmlspecialchars($u['Address']); ?></textarea><br>
    <input type="submit" value="Save">
</form>
