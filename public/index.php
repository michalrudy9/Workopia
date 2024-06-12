<?php

require_once "../helpers.php";
require_once basePath("Router.php");
require_once basePath("Database.php");

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

$router = new Router();
$routes = require_once basePath("routes.php");
$router->route($uri, $method);
