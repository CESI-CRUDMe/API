<?php

namespace App\classes;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class Router {
    private array $routes = [];
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addRoute(string $method, string $path, array $action, bool $isPublic = true): void {
        // S'assurer que le chemin commence par un slash
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $action[0],
            'action' => $action[1],
            'isPublic' => $isPublic,
            'isApi' => explode('/', $path)[1] === 'api'
        ];
    }

    private function matchRoute(string $requestMethod, string $requestPath): ?array {
        // S'assurer que le requestPath commence par un slash
        if (!str_starts_with($requestPath, '/')) {
            $requestPath = '/' . $requestPath;
        }
        foreach ($this->routes as $route) {
            
            if (trim($route['method']) !== $requestMethod) {
                continue;
            }
            $pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = "@^" . $pattern . "$@D";
            unset($parameters);
            if (preg_match($pattern, $requestPath, $parameters)) {
                return [
                    'route' => $route,
                    'parameters' => array_filter($parameters, 'is_string', ARRAY_FILTER_USE_KEY)
                ];
            }
        }
        return null;
    }

    private function verifyJWT() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return false;
        }

        $jwt = str_replace('Bearer ', '', $headers['Authorization']);
        
        // Vérification basique du format du token (trois segments séparés par des points)
        if (empty($jwt) || substr_count($jwt, '.') !== 2) {
            return false;
        }

        try {
            $decoded = JWT::decode($jwt, new Key(JWT_KEY, 'HS256'));
            
            // Vérification de l'expiration
            if (isset($decoded->exp) && time() > $decoded->exp) {
                return false;
            }

            return $decoded;
        } catch (ExpiredException $e) {
            return false;
        } catch (\UnexpectedValueException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function dispatch(string $requestMethod, string $requestPath) {
        $match = $this->matchRoute($requestMethod, $requestPath);
        if ($match === null) {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            return;
        }

        if (!$match['route']['isPublic'] && $match['route']['isApi']) {
            if (!$this->verifyJWT()) {
                http_response_code(498);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }
        }

        if ($match['route']['isApi']) {
            header('Content-Type: application/json');
        }

        $controllerClass = $match['route']['controller'];
        $action = $match['route']['action'];
        $parameters = $match['parameters'];

        $controller = new $controllerClass($this->pdo);
        return $controller->$action($parameters);
    }

    public static function render(string $view, array $data = []): void {
        extract($data);
    var_dump('ROOT:', ROOT);
    var_dump('view:', $view);
    var_dump('full path:', ROOT . '/views/' . $view . '.php');
    if (file_exists(ROOT . '/views/' . $view . '.php')) {
        require ROOT . '/views/' . $view . '.php';
    } else {
        require ROOT . '/views/errors/404.php';
    }
    }
} 