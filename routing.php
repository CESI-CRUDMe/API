<?php

/********** Autoloader **********/

function autoload($class)
{
    $prefixes = [
        'app\controllers' => __DIR__ . '/controllers/',
        'app\models' => __DIR__ . '/models/'
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
$path = explode('?', $path);
$path = $path[0];

if (strpos($path, '/') === 0)
$path = substr($path, 1);

$path = explode('/', $path);


switch ($path[0]) {
    case 'posts':
if (isset($path[1])) {
            switch ($path[1]) {
                case 'migrate':
                    Post::migrate($pdo);
                    echo json_encode(['message' => 'Migration done']);
                    die();
            }
        }
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