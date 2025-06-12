<?php

namespace app\controllers;

use PDO;

class PostsController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $title = $data['title'];
        $content = $data['content'];
        $stmt = $this->pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);
        echo json_encode(['message' => 'Post created successfully']);
    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $title = $data['title'];
        $content = $data['content'];
        $stmt = $this->pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);
        echo json_encode(['message' => 'Post updated successfully']);
    }

    public function delete()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Post deleted successfully']);
    }

    public function show()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['post' => $post]);
    }
}