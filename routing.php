<?php

/********** Autoloader **********/

function autoload($class)
{
    $prefixes = [
        'app\controllers' => __DIR__ . '/controllers/'
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





/********** Routing **********/
var_dump($_SERVER);

$path = $_SERVER['REQUEST_URI'];

$path = str_replace('/api.crudme.mindlens.fr/', '', $path);

var_dump($path);

$path = explode('/', $path);

switch ($path[0]) {
    case 'posts':
        switch($_SERVER['REQUEST_METHOD']){
            case 'GET':
                $postsController = new PostsController($pdo);
                $postsController->show();
                break;
            case 'POST':
                $postsController = new PostsController($pdo);
                $postsController->create();
                break;
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}
/********** Routing **********/