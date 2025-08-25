<?php

use App\controllers\PostsController;
use App\controllers\PostsViewController;
use App\controllers\AuthController;
use App\controllers\TestController;
use App\controllers\HomeController;
use App\classes\Router;

/********** Routes Configuration **********/
$router = new Router($pdo);

/************ Routes Frontend ************/
$router->addRoute('GET', '/', [HomeController::class, 'index'], true);
$router->addRoute('GET', '/test', [TestController::class, 'index'], true);
$router->addRoute('GET', '/posts/create', [PostsViewController::class, 'create'], true);
$router->addRoute('GET', '/posts', [PostsViewController::class, 'index'], true);
$router->addRoute('GET', '/posts/{id}', [PostsViewController::class, 'show'], true);
$router->addRoute('GET', '/posts/{id}/pdf', [PostsViewController::class, 'pdf'], true); // export pdf single
$router->addRoute('GET', '/posts/pdf/all', [PostsViewController::class, 'pdfAll'], true); // export pdf all
$router->addRoute('GET', '/posts/{id}/edit', [PostsViewController::class, 'edit'], true); // nouvelle route édition
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
