<?php
// db/connection.php

$host = "localhost";
$user = "root";  // XAMPP default
$pass = "";      // XAMPP default password empty
$dbname = "article_management";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
