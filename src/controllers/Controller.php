<?php

namespace App\controllers;

use PDO;

class Controller {

    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    protected function isAuthenticated(): bool {
        if(session_status() === PHP_SESSION_NONE) { session_start(); }
        if(isset($_SESSION['auth'])) { return true; }
        // Vérif JWT (rapide) si autorisation envoyée
        if(function_exists('getallheaders')) {
            $headers = getallheaders();
            if(isset($headers['Authorization'])) {
                $jwt = str_replace('Bearer ', '', $headers['Authorization']);
                if(substr_count($jwt, '.') === 2) {
                    try {
                        \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key(JWT_KEY, 'HS256'));
                        return true;
                    } catch(\Throwable $e) { /* ignore */ }
                }
            }
        }
        return false;
    }
}