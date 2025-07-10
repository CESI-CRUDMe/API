<?php

namespace App\controllers;

use PDO;

class Controller {

    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}