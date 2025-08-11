<?php
session_start();
require_once __DIR__ . '/../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);

    // Check user credentials
    $sql = "SELECT * FROM users WHERE User_Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password - assuming passwords are hashed
        if (password_verify($password, $user['Password'])) {
            // Set session variables
            $_SESSION['userId'] = $user['userId'];
            $_SESSION['username'] = $user['User_Name'];
            $_SESSION['userType'] = $user['UserType'];
            $_SESSION['Full_Name'] = $user['Full_Name'];

            // Redirect based on UserType
            if ($user['UserType'] === 'Super_User') {
                header("Location: ../views/dashboard.php");
            } elseif ($user['UserType'] === 'Administrator') {
                header("Location: ../views/dashboard.php");
            } elseif ($user['UserType'] === 'Author') {
                header("Location: ../views/dashboard.php");
            } else {
                header("Location: ../views/login.php?error=unauthorized");
            }
            exit;
        } else {
            header("Location: ../views/login.php?error=wrongpassword");
            exit;
        }
    } else {
        header("Location: ../views/login.php?error=usernotfound");
        exit;
    }
} else {
    header("Location: ../views/login.php");
    exit;
}
