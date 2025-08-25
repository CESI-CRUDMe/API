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

    public function login()
    {
        if(session_status()===PHP_SESSION_NONE){ session_start(); }
        if(isset($_SESSION['auth'])){ header('Location: /posts'); exit; }
        Router::render('home/login');
    }
}