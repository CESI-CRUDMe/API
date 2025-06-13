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

    public function index(): void
    {
        $posts = Post::getAll($this->pdo);
        echo htmlspecialchars(json_encode(['posts' => $posts]), ENT_QUOTES, 'UTF-8');
    }

    public function show(int $id): void
    {
        $post = new Post();
        $post->id = $id;
        $post = $post->get($this->pdo);
        if(!$post){
            http_response_code(404);
            echo json_encode(['message' => 'Post not found']);
            die();
        }
        echo htmlspecialchars(json_encode(['post' => $post]), ENT_QUOTES, 'UTF-8');
    }

    public function create($data): void
    {
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
        echo htmlspecialchars(json_encode(['message' => 'Post created successfully']), ENT_QUOTES, 'UTF-8');
    }

    public function update($data): void
    {
        if(!isset($data['id'])){
            http_response_code(400);
            echo json_encode(['message' => 'Missing required fields']);
            die();
        }

        $post = new Post();
        $post->id = $data['id'];
        foreach($data as $key => $value){
            if($key !== 'id'){
                $post->$key = $value;
            }
        }
        $post->update($this->pdo);
        echo htmlspecialchars(json_encode(['message' => 'Post updated successfully']), ENT_QUOTES, 'UTF-8');
    }

    public function delete($data): void
    {
        $post = new Post();
        $post->id = $data['id'];
        $post->delete($this->pdo);
        echo htmlspecialchars(json_encode(['message' => 'Post deleted successfully']), ENT_QUOTES, 'UTF-8');
    }
}