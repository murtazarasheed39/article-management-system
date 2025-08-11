<?php
session_start();
require_once __DIR__ . '/../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phone_number']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $address = trim($_POST['address']);
    $userType = $_POST['user_type'];
    $accessTime = date('Y-m-d H:i:s');

    // Handle profile image upload
    $profileImageName = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/profile_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $tmpName = $_FILES['profile_image']['tmp_name'];
        $originalName = basename($_FILES['profile_image']['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $profileImageName = uniqid('profile_', true) . '.' . $ext;
        $uploadFilePath = $uploadDir . $profileImageName;

        if (!move_uploaded_file($tmpName, $uploadFilePath)) {
            header("Location: ../views/register.php?error=Failed to upload profile image.");
            exit;
        }
    }

    // Basic validation
    if (empty($fullName) || empty($email) || empty($username) || empty($password) || empty($userType)) {
        header("Location: ../views/register.php?error=Please fill all required fields.");
        exit;
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE User_Name = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: ../views/register.php?error=Username or Email already exists.");
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user with profile image and address
    $stmt = $conn->prepare("INSERT INTO users (Full_Name, email, phone_Number, User_Name, Password, UserType, AccessTime, profile_Image, Address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $fullName, $email, $phoneNumber, $username, $hashedPassword, $userType, $accessTime, $profileImageName, $address);
    
    if ($stmt->execute()) {
        header("Location: ../views/login.php?success=Registration successful. Please log in.");
        exit;
    } else {
        header("Location: ../views/register.php?error=Registration failed. Try again.");
        exit;
    }

} else {
    header("Location: ../views/register.php");
    exit;
}
