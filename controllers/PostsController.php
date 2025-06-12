<?php

namespace app\controllers;

use PDO;
use app\models\Post;

class PostsController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function create(): void
    {
        $data = $_POST;
        $post = new Post();

        if(!isset($data['title']) || !isset($data['content']) || !isset($data['price']) || !isset($data['latitude']) || !isset($data['longitude']) || !isset($data['contact_name']) || !isset($data['contact_phone'])){
            http_response_code(400);
            echo json_encode(['message' => 'Missing required fields']);
            die();
        }

        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->price = $data['price'];
        $post->latitude = $data['latitude'];
        $post->longitude = $data['longitude'];
        $post->contact_name = $data['contact_name'];
        $post->contact_phone = $data['contact_phone'];
        $post->create($this->pdo);
        echo json_encode(['message' => 'Post created successfully']);
    }

    public function update(): void
    {
        $data = $_POST;
        $post = new Post();
        $post->id = $data['id'];
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->price = $data['price'];
        $post->latitude = $data['latitude'];
        $post->longitude = $data['longitude'];
        $post->contact_name = $data['contact_name'];
        $post->contact_phone = $data['contact_phone'];
        $post->update($this->pdo);
        echo json_encode(['message' => 'Post updated successfully']);
    }

    public function delete(): void
    {
        $data = $_POST;
        $post = new Post();
        $post->id = $data['id'];
        $post->delete($this->pdo);
        echo json_encode(['message' => 'Post deleted successfully']);
    }

    public function show(): void
    {
        $data = $_POST;
        $post = new Post();
        $post->id = $data['id'];
        $post->get($this->pdo);
        echo json_encode(['post' => $post]);
    }
}