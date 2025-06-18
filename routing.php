<?php

use app\classes\Router;
use app\controllers\PostsController;
use app\controllers\AuthController;
use app\controllers\TestController;

/********** Autoloader **********/
function autoload($class)
{
    $prefixes = [
        'app\controllers' => __DIR__ . '/controllers/',
        'app\models' => __DIR__ . '/models/',
        'app\classes' => __DIR__ . '/classes/'
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (strpos($class, $prefix) === 0) {
            $relativeClass = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        }
    }
}

spl_autoload_register('autoload');
/********** Autoloader **********/

/********** Routes Configuration **********/
$router = new Router($pdo);

// Définition des routes
$router->addRoute('GET', 'posts', [PostsController::class, 'index']);
$router->addRoute('GET', 'posts/{id}', [PostsController::class, 'show']);
$router->addRoute('POST', 'posts', [PostsController::class, 'create']);
$router->addRoute('PUT', 'posts/{id}', [PostsController::class, 'update']);
$router->addRoute('DELETE', 'posts/{id}', [PostsController::class, 'delete']);
$router->addRoute('GET', 'posts/migrate', [PostsController::class, 'migrate']);

$router->addRoute('POST', 'jwt', [AuthController::class, 'jwt']);

$router->addRoute('GET', 'test', [TestController::class, 'index']);

// Récupération du chemin de la requête
$path = $_SERVER['REQUEST_URI'];
$path = explode('?', $path)[0];
$path = trim($path, '/');

// Dispatch de la requête
$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
/********** Routes Configuration **********/
