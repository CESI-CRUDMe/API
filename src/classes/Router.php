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
            // Désormais seule la présence d'un JWT valide est acceptée (pas de fallback session)
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
        $parameters = $match['parameters']; // tableau associatif clé => valeur (ex: ['id'=>123]) ou vide

        $controller = new $controllerClass($this->pdo);

        // Invocation intelligente pour éviter les erreurs ArgumentCountError
        try {
            $refMethod = new \ReflectionMethod($controller, $action);
            $refParams = $refMethod->getParameters();
            $paramCount = count($refParams);

            if ($paramCount === 0) {
                return $controller->$action();
            }

            if ($paramCount === 1) {
                $p = $refParams[0];
                $type = $p->hasType() ? $p->getType() : null;
                $isArrayType = $type && ($type instanceof \ReflectionNamedType) && $type->getName() === 'array';

                // Si le param attendu n'est pas un array et qu'on a exactement une valeur, on passe la valeur seule
                if (!$isArrayType && count($parameters) === 1) {
                    $value = array_values($parameters)[0];
                    return $controller->$action($value);
                }
                // Sinon on passe le tableau complet (ex: méthode attend array $params)
                return $controller->$action($parameters);
            }

            // Méthode avec plusieurs paramètres : on tente d'aligner par nom si possible
            $args = [];
            foreach ($refParams as $p) {
                $name = $p->getName();
                if (array_key_exists($name, $parameters)) {
                    $args[] = $parameters[$name];
                } elseif ($p->isDefaultValueAvailable()) {
                    $args[] = $p->getDefaultValue();
                } else {
                    // Param manquant : on peut lever une erreur ou passer null
                    $args[] = null;
                }
            }
            return $controller->$action(...$args);
        } catch (\ReflectionException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Controller action not callable']);
            return;
        }
    }

    public static function render(string $view, array $data = []): void {
        extract($data);
        if (file_exists(ROOT . '/src/views/' . $view . '.php')) {
            require ROOT . '/src/views/templates/header.tpl.php';
            require ROOT . '/src/views/' . $view . '.php';
            require ROOT . '/src/views/templates/footer.tpl.php';
        } else {
            http_response_code(404);
            require ROOT . '/src/views/errors/404.php';
        }
    }
}