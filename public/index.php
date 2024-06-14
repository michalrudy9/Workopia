<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once "../helpers.php";

use Framework\Router;
use Framework\Session;

Session::start();

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$router = new Router();
$routes = require_once basePath("routes.php");
$router->route($uri);
