<?php
session_start();
require_once __DIR__ . '/../db/connection.php';

if (!isset($_SESSION['User_Name'])) {
    header("Location: ../views/login.php");
    exit;
}

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phone_number']);
    $address = trim($_POST['address']);

    // Basic validation
    if (empty($fullName) || empty($email)) {
        header("Location: ../views/update_profile.php?error=Full Name and Email are required.");
        exit;
    }

    // Handle profile image upload (optional)
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
            header("Location: ../views/update_profile.php?error=Failed to upload profile image.");
            exit;
        }

        // Delete old image file if exists
        $stmt = $conn->prepare("SELECT profile_Image FROM users WHERE userId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $oldUser = $result->fetch_assoc();

        if ($oldUser && $oldUser['profile_Image'] && file_exists($uploadDir . $oldUser['profile_Image'])) {
            unlink($uploadDir . $oldUser['profile_Image']);
        }
    }

    // Update query - conditionally update profile image
    if ($profileImageName) {
        $stmt = $conn->prepare("UPDATE users SET Full_Name = ?, email = ?, phone_Number = ?, Address = ?, profile_Image = ? WHERE userId = ?");
        $stmt->bind_param("sssssi", $fullName, $email, $phoneNumber, $address, $profileImageName, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET Full_Name = ?, email = ?, phone_Number = ?, Address = ? WHERE userId = ?");
        $stmt->bind_param("ssssi", $fullName, $email, $phoneNumber, $address, $userId);
    }

    if ($stmt->execute()) {
        // Update session full name and email if needed
        $_SESSION['Full_Name'] = $fullName;
        $_SESSION['email'] = $email;
        header("Location: ../views/update_profile.php?success=Profile updated successfully.");
        exit;
    } else {
        header("Location: ../views/update_profile.php?error=Failed to update profile.");
        exit;
    }
} else {
    header("Location: ../views/update_profile.php");
    exit;
}
