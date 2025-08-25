<?php

namespace App\controllers;

use PDO;
use Firebase\JWT\JWT;
use Exception;
use App\models\User; // ajout

class AuthController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }

    public function jwt(): void
    {
        if(session_status()===PHP_SESSION_NONE){ session_start(); }
        if(!isset($_SESSION['auth'])){ http_response_code(401); echo json_encode(['message'=>'Unauthorized']); return; }
        $payload = [
            'iss' => 'https://api.crudme.mindlens.fr',
            'aud' => 'https://api.crudme.mindlens.fr',
            'iat' => time(),
            'exp' => time() + 300, // 5 min maintenant
            'user' => $_SESSION['auth']['email'] ?? null,
            'uid' => $_SESSION['auth']['id'] ?? null
        ];
        try {
            $encodedToken = JWT::encode($payload, JWT_KEY, 'HS256');
            echo json_encode([
                'token' => $encodedToken,
                'expires_in' => $payload['exp'] - $payload['iat']
            ]);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized', 'details' => $e->getMessage()]);
        }
    }

    // Login form POST (DB users)
    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $email = $data['email'] ?? $data['username'] ?? '';
        $pass = $data['password'] ?? '';

        if (!$email || !$pass) {
            http_response_code(400);
            echo json_encode(['message' => 'Email et mot de passe requis']);
            return;
        }

        // Récupération user
        $user = User::findByEmail($this->pdo, $email);
        if ($user && password_verify($pass, $user['password_hash'])) {
            // Regénérer l'ID de session pour éviter fixation
            session_regenerate_id(true);
            $_SESSION['auth'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'login_time' => time()
            ];
            echo json_encode(['message' => 'ok']);
            return;
        }
        http_response_code(401);
        echo json_encode(['message' => 'Identifiants invalides']);
    }

    public function status(): void
    {
        $logged = isset($_SESSION['auth']);
        echo json_encode(['authenticated' => $logged, 'user' => $logged ? $_SESSION['auth']['email'] : null]);
    }

    public function logout(): void
    {
        $_SESSION = [];
        if(session_id() !== '' || isset($_COOKIE[session_name()])){
            setcookie(session_name(), '', time()-42000, '/');
        }
        session_destroy();
        echo json_encode(['message' => 'logged_out']);
    }
}