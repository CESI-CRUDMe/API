<?php

namespace app\controllers;

use PDO;
use Firebase\JWT\JWT;
use Exception;

class AuthController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function jwt(): void
    {
        $payload = [
            'iss' => 'https://api.crudme.mindlens.fr',
            'aud' => 'https://api.crudme.mindlens.fr',
            'iat' => time(),
            'exp' => time() + 20,
            'email' => 'teomullerheddar@gmail.com',
        ];

        try {
            
            $encoded = JWT::encode($payload, JWT_KEY, 'HS256');
            echo json_encode([
                'token' => $encoded,
                'expires_in' => $payload['exp'] - $payload['iat']
            ]);
        } catch (Exception $e) {
            # code...
            echo json_encode(['message' => 'Unauthorized', 'details' => $e->getMessage()]);
            die();
        }
        
    }
}