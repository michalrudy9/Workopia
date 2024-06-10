<?php

require_once "../helpers.php";
require_once basePath("Router.php");

$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

$router = new Router();
$routes = require_once basePath("routes.php");
$router->route($uri, $method);
