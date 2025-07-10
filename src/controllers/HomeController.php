<?php

namespace App\controllers;

use PDO;
use App\classes\Router;
use App\controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        Router::render('home/index');
    }
}