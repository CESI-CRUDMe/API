<?php

use App\controllers\PostsController;
use App\controllers\AuthController;
use App\controllers\TestController;
use App\controllers\HomeController;
use App\classes\Router;

/********** Routes Configuration **********/
$router = new Router($pdo);

/************ Routes Frontend ************/
$router->addRoute('GET', '/', [HomeController::class, 'index'], true);
$router->addRoute('GET', '/create', [HomeController::class, 'create'], true);
$router->addRoute('POST', '/ajax', [HomeController::class, 'ajax'], false);
$router->addRoute('GET', '/{id}', [HomeController::class, 'show'], true);

/************ Routes Frontend ************/


/************ Routes API ************/
// Posts
$router->addRoute('GET', '/api/posts', [PostsController::class, 'index'], true);
$router->addRoute('GET', '/api/posts/{id}', [PostsController::class, 'show'], true);
$router->addRoute('POST', '/api/posts', [PostsController::class, 'create'], false);
$router->addRoute('PUT', '/api/posts/{id}', [PostsController::class, 'update'], false);
$router->addRoute('DELETE', '/api/posts/{id}', [PostsController::class, 'delete'], false);
$router->addRoute('GET', '/api/posts/migrate', [PostsController::class, 'migrate'], false);

// Auth
$router->addRoute('POST', '/api/jwt', [AuthController::class, 'jwt'], true);

// Test
$router->addRoute('GET', '/api/test', [TestController::class, 'index'], true);
/************ Routes API ************/


// Récupération du chemin de la requête
$path = $_SERVER['REQUEST_URI'];
$path = explode('?', $path)[0];
// On ne retire que le slash final si présent
$path = rtrim($path, '/');

// Dispatch de la requête
$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
/********** Routes Configuration **********/
