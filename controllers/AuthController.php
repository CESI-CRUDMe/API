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
        $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        if (!isset($data['token']) || $data['token'] !== JWT_REFRESH_KEY) {
            echo json_encode(['message' => 'Unauthorized']);
            die();
        }

        $payload = [
            'iss' => 'https://api.crudme.mindlens.fr',
            'aud' => 'https://api.crudme.mindlens.fr',
            'iat' => time(),
            'exp' => time() + 30,
            'email' => 'teomullerheddar@gmail.com',
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
            die();
        }

    }
}