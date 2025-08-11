<?php
// classes/Article.php

class Article {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO articles (authorId, article_title, article_full_text, article_display, article_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi",
            $data['authorId'],
            $data['article_title'],
            $data['article_full_text'],
            $data['article_display'],
            $data['article_order']
        );
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE articles SET article_title=?, article_full_text=?, article_display=?, article_order=? WHERE articleId=?");
        $stmt->bind_param("sssii",
            $data['article_title'],
            $data['article_full_text'],
            $data['article_display'],
            $data['article_order'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE articleId = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT a.*, u.Full_Name FROM articles a JOIN users u ON a.authorId = u.userId WHERE articleId = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getLastN($n = 6) {
        $stmt = $this->conn->prepare("SELECT a.*, u.Full_Name FROM articles a JOIN users u ON a.authorId = u.userId WHERE a.article_display='yes' ORDER BY a.article_created_date DESC LIMIT ?");
        $stmt->bind_param("i", $n);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByAuthor($authorId) {
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE authorId = ? ORDER BY article_created_date DESC");
        $stmt->bind_param("i", $authorId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT a.*, u.Full_Name FROM articles a JOIN users u ON a.authorId = u.userId ORDER BY a.article_created_date DESC");
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
