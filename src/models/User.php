<?php

namespace App\models;

use PDO;

class User
{
    public int $id;
    public string $email;
    public string $password_hash;

    public static function migrate(PDO $pdo): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (\n            id INT AUTO_INCREMENT PRIMARY KEY,\n            email VARCHAR(190) NOT NULL UNIQUE,\n            password_hash VARCHAR(255) NOT NULL,\n            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $pdo->exec($sql);
    }

    public function create(PDO $pdo, string $plainPassword): void
    {
        $this->password_hash = password_hash($plainPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");
        $stmt->execute(['email' => $this->email, 'password_hash' => $this->password_hash]);
        $this->id = (int)$pdo->lastInsertId();
    }

    public static function findByEmail(PDO $pdo, string $email): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}
