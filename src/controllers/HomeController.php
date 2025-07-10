<?php

namespace App\controllers;

use PDO;
use App\classes\Router;
use App\controllers\PostsController;
use App\models\Post;

class HomeController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $posts = Post::getAll($this->pdo);
        Router::render('home/index', ['posts' => $posts]);
    }

    public function show($id)
    {
        $post = Post::getById($this->pdo, $id);
        Router::render('home/show', ['post' => $post]);
    }

    public function create()
    {
        //die('create');
        Router::render('home/create');
    }

    public function ajax()
    {
        if(isset($_POST['title']) && isset($_POST['content'])) {
            $postsController = new PostsController($this->pdo);
            $postsController->create($_POST); 
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}