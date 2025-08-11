-- init_db.sql
CREATE DATABASE IF NOT EXISTS article_management;
USE article_management;

-- USERS Table
CREATE TABLE IF NOT EXISTS users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    Full_Name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_Number VARCHAR(20),
    User_Name VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    UserType ENUM('Super_User','Administrator','Author') NOT NULL,
    AccessTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    profile_Image VARCHAR(255),
    Address TEXT
);

-- ARTICLES Table
CREATE TABLE IF NOT EXISTS articles (
    articleId INT AUTO_INCREMENT PRIMARY KEY,
    authorId INT NOT NULL,
    article_title VARCHAR(255) NOT NULL,
    article_full_text TEXT NOT NULL,
    article_created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    article_last_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    article_display ENUM('yes','no') DEFAULT 'yes',
    article_order INT DEFAULT 0,
    FOREIGN KEY (authorId) REFERENCES users(userId) ON DELETE CASCADE
);

-- Insert a Super_User (password hashed using PHP password_hash)
-- Password: Super@123
INSERT INTO users
(Full_Name, email, phone_Number, User_Name, Password, UserType, Address)
VALUES (
 'Super Administrator',
 'super@yourdomain.com',
 '0700000000',
 'superadmin',
 '$2y$10$e0NR6d4e0gAxQv4z1Jf0PuCQLQq0Jp6n/0xZr1t3cQhd0KzV.7B2e', -- hash of Super@123 (example)
 'Super_User',
 'Admin Office'
);
