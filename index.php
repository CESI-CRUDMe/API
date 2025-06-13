<?php

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

//require 'jwt.php';

require 'config.php';
require 'database.php';
require 'routing.php';
