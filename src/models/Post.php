<?php

namespace App\models;

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
    public ?string $image_base64 = null; // data URI complète (ex: data:image/png;base64,...)
    public ?DateTime $created_at = null;
    public ?DateTime $updated_at = null;

    public function create(PDO $pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, price, latitude, longitude, contact_name, contact_phone, image_base64) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$this->title, $this->content, $this->price, $this->latitude, $this->longitude, $this->contact_name, $this->contact_phone, $this->image_base64]);
        $this->id = (int)$pdo->lastInsertId();
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
        if ($this->image_base64 !== null) {
            $fields[] = "image_base64 = ?";
            $values[] = $this->image_base64;
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

    public static function getAll(PDO $pdo, ?int $page = null, ?int $limit = null)
    {
        $sql = "SELECT * FROM posts";
        if ($page && $limit) {
            $offset = ($page - 1) * $limit;
            $sql .= " LIMIT $limit OFFSET $offset";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function migrate(PDO $pdo)
    {
        $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS posts (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255), content TEXT, price DECIMAL(10, 2), latitude DECIMAL(10,6), longitude DECIMAL(10,6), contact_name VARCHAR(255), contact_phone VARCHAR(255), image_base64 MEDIUMTEXT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)");
        $stmt->execute();
        // Tentative suppression colonne image_path si existait
        try { $pdo->exec("ALTER TABLE posts DROP COLUMN image_path"); } catch(\Throwable $e) { /* ignore */ }
    }

    public static function getById(PDO $pdo, mixed $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getFiltered(PDO $pdo, ?string $q, string $sort = 'new') : array
    {
        $sql = "SELECT * FROM posts";
        $where = [];
        $params = [];
        if ($q !== null && $q !== '') {
            $where[] = "(title LIKE :q OR content LIKE :q OR contact_name LIKE :q)"; // contact_name assimilé à author
            $params['q'] = "%" . $q . "%";
        }
        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        switch($sort) {
            case 'old':
                $sql .= " ORDER BY created_at ASC";
                break;
            case 'title':
                $sql .= " ORDER BY title ASC";
                break;
            default: // new
                $sql .= " ORDER BY created_at DESC";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFilteredPaginated(PDO $pdo, ?string $q, string $sort = 'new', int $limit = 9, int $page = 1): array
    {
        $where = [];
        $params = [];
        if ($q !== null && $q !== '') {
            $where[] = "(title LIKE :q OR content LIKE :q OR contact_name LIKE :q)";
            $params['q'] = "%" . $q . "%";
        }
        // Total count
        $countSql = "SELECT COUNT(*) FROM posts";
        if ($where) { $countSql .= " WHERE " . implode(' AND ', $where); }
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        // Data query
        $sql = "SELECT * FROM posts";
        if ($where) { $sql .= " WHERE " . implode(' AND ', $where); }
        switch($sort) {
            case 'old': $sql .= " ORDER BY created_at ASC"; break;
            case 'title': $sql .= " ORDER BY title ASC"; break;
            default: $sql .= " ORDER BY created_at DESC"; // new
        }
        $offset = max(0, ($page - 1) * $limit);
        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        foreach($params as $k=>$v){ $stmt->bindValue($k, $v, PDO::PARAM_STR); }
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['data' => $data, 'total' => $total];
    }
}