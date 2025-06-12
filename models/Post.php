<?php

namespace app\models;

use DateTime;
use PDO;

class Post
{
    public int $id;
    public string $title;
    public string $content;
    public float $price;
    public float $latitude;
    public float $longitude;
    public string $contact_name;
    public string $contact_phone;
    public DateTime $created_at;
    public DateTime $updated_at;

    public function create(PDO $pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, price, latitude, longitude, contact_name, contact_phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$this->title, $this->content, $this->price, $this->latitude, $this->longitude, $this->contact_name, $this->contact_phone]);
    }

    public function update(PDO $pdo)
    {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, price = ?, latitude = ?, longitude = ?, contact_name = ?, contact_phone = ? WHERE id = ?");
        $stmt->execute([$this->title, $this->content, $this->price, $this->latitude, $this->longitude, $this->contact_name, $this->contact_phone, $this->id]);
    }

    public function delete(PDO $pdo)
    {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$this->id]);
    }

    public function get(PDO $pdo)
    {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(PDO $pdo)
    {
        $stmt = $pdo->prepare("SELECT * FROM posts");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function migrate(PDO $pdo)
    {
        $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS posts (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255), content TEXT, price DECIMAL(10, 2), latitude DECIMAL, longitude DECIMAL, contact_name VARCHAR(255), contact_phone VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)");
        $stmt->execute();
    }
}