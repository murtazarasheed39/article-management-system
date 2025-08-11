<?php
// filepath: article_management\src\views\signin.php
session_start();
require_once __DIR__ . '/../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['User_Name'];
    $password = $_POST['Password'];

    $stmt = $mysqli->prepare("SELECT userId, User_Name, Password, UserType FROM users WHERE User_Name = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userId, $User_Name, $hashedPassword, $UserType);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['userId'] = $userId;
            $_SESSION['User_Name'] = $User_Name;
            $_SESSION['UserType'] = $UserType;
            header('Location: dashboard.php');
            exit();
        }
    }
    header('Location: index.php?error=Invalid credentials');
    exit();
}
header('Location: index.php');
exit();