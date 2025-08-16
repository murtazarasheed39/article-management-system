<?php
// config/database.php

$host = "localhost";   // XAMPP default
$user = "root";        // default XAMPP username
$pass = "";            // default XAMPP has empty password
$db   = "article_management";  // database name from init_db.sql

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
