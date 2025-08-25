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

    public function index($params = []): void
    {
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;
        $posts = Post::getAll($this->pdo, $page, $limit);
        echo json_encode(['posts' => $posts]);
    }

    public function show($params): void
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID is required']);
            die();
        }

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
        $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $post = new Post();

        if(!isset($data['title']) || !isset($data['content']) || !isset($data['price']) || !isset($data['latitude']) || !isset($data['longitude']) || !isset($data['contact_name']) || !isset($data['contact_phone'])){
            http_response_code(400);
            echo json_encode(['message' => 'Missing required fields']);
            die();
        }

        $post->title = htmlspecialchars($data['title']);
        $post->content = htmlspecialchars($data['content']);
        $post->price = floatval($data['price']);
        $post->latitude = floatval($data['latitude']);
        $post->longitude = floatval($data['longitude']);
        $post->contact_name = htmlspecialchars($data['contact_name']);
        $post->contact_phone = htmlspecialchars($data['contact_phone']);
        $post->create($this->pdo);
        echo htmlspecialchars(json_encode(['message' => 'Post created successfully']), ENT_QUOTES, 'UTF-8');
    }
    public function update($params): void
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID is required']);
            die();
        }

        $data = json_decode(file_get_contents('php://input'), true) ?? $_REQUEST;
        $post = new Post();
        $post->id = $id;
        
        foreach($data as $key => $value){
            if($key !== 'id' && property_exists($post, $key)){
                $post->$key = $value;
            }
        }
        
        $post->update($this->pdo);
        echo htmlspecialchars(json_encode(['message' => 'Post updated successfully']), ENT_QUOTES, 'UTF-8');
    }

    public function delete($params): void
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID is required']);
            die();
        }
        $post = new Post();
        $post->id = $id;
        $post->delete($this->pdo);
        echo htmlspecialchars(json_encode(['message' => 'Post deleted successfully']), ENT_QUOTES, 'UTF-8');
    }

    public function migrate(): void
    {
        Post::migrate($this->pdo);
        echo json_encode(['message' => 'Migration completed successfully']);
    }
}