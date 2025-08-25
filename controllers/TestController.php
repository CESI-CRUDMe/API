<?php

namespace app\controllers;

use PDO;
use app\models\Post;

class TestController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index($params = []): void
    {
        echo json_encode(['message' => 'Hello World']);
    }
}