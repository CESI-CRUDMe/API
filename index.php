<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 3600');

// Démarrer la session pour la gestion d'authentification
if(session_status() === PHP_SESSION_NONE){ session_start(); }

// Gestion des requêtes OPTIONS (pre-flight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Définir le fuseau horaire sur Paris
date_default_timezone_set('Europe/Paris');

/********** Autoloader **********/
require 'vendor/autoload.php';
/********** Autoloader **********/

require 'config.php';
require 'database.php';

// Ajuster le fuseau côté MySQL (utilise l'offset actuel de Paris, gère automatiquement l'heure d'été via PHP)
try { if(isset($pdo)) { $pdo->exec("SET time_zone='" . date('P') . "'"); } } catch (\Exception $e) {}

require 'routing.php';
