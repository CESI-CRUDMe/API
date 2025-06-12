<?php

namespace app\models;

use DateTime;
use PDO;

class Post
{
    public int $id;
    public ?string $title = null;
    public ?string $content = null;
    public ?float $price = null;
    public ?float $latitude = null;
    public ?float $longitude = null;
    public ?string $contact_name = null;
    public ?string $contact_phone = null;
    public ?DateTime $created_at = null;
    public ?DateTime $updated_at = null;

    public function create(PDO $pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, price, latitude, longitude, contact_name, contact_phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$this->title, $this->content, $this->price, $this->latitude, $this->longitude, $this->contact_name, $this->contact_phone]);
    }

    public function update(PDO $pdo)
    {
        // Construire la requête dynamiquement en fonction des propriétés définies
        $fields = [];
        $values = [];
        
        if ($this->title !== null) {
            $fields[] = "title = ?";
            $values[] = $this->title;
        }
        if ($this->content !== null) {
            $fields[] = "content = ?";
            $values[] = $this->content;
        }
        if ($this->price !== null) {
            $fields[] = "price = ?";
            $values[] = $this->price;
        }
        if ($this->latitude !== null) {
            $fields[] = "latitude = ?";
            $values[] = $this->latitude;
        }
        if ($this->longitude !== null) {
            $fields[] = "longitude = ?";
            $values[] = $this->longitude;
        }
        if ($this->contact_name !== null) {
            $fields[] = "contact_name = ?";
            $values[] = $this->contact_name;
        }
        if ($this->contact_phone !== null) {
            $fields[] = "contact_phone = ?";
            $values[] = $this->contact_phone;
        }
        
        if (empty($fields)) {
            return; 
        }
        
        $values[] = $this->id; // Ajouter l'ID pour la clause WHERE
        $sql = "UPDATE posts SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
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

    public static function getAll(PDO $pdo)
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