<?php
// classes/User.php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Get user by username
    public function getByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE User_Name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE userId = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Create user
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO users (Full_Name, email, phone_Number, User_Name, Password, UserType, profile_Image, Address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss",
            $data['Full_Name'],
            $data['email'],
            $data['phone_Number'],
            $data['User_Name'],
            $data['Password'],     // make sure password is hashed before passing here
            $data['UserType'],
            $data['profile_Image'],
            $data['Address']
        );
        return $stmt->execute();
    }

    // Update user by id (password optional)
    public function update($id, $data) {
        if (!empty($data['Password'])) {
            $stmt = $this->conn->prepare("UPDATE users SET Full_Name=?, email=?, phone_Number=?, Password=?, profile_Image=?, Address=? WHERE userId=?");
            $stmt->bind_param("ssssssi",
                $data['Full_Name'],
                $data['email'],
                $data['phone_Number'],
                $data['Password'],     // hashed password if updating
                $data['profile_Image'],
                $data['Address'],
                $id
            );
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET Full_Name=?, email=?, phone_Number=?, profile_Image=?, Address=? WHERE userId=?");
            $stmt->bind_param("sssssi",
                $data['Full_Name'],
                $data['email'],
                $data['phone_Number'],
                $data['profile_Image'],
                $data['Address'],
                $id
            );
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE userId = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getAllExceptSuper() {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE UserType != 'Super_User' ORDER BY userId DESC");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getAllByType($type) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE UserType = ? ORDER BY userId DESC");
        $stmt->bind_param("s", $type);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY userId DESC");
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
