<?php

namespace Framework;

use App\Controllers\ErrorController;
use Framework\Middleware\Authorize;

class Router
{
    protected array $routes = [];

    public function registerRoute(
        string $method,
        string $uri,
        string $action,
        array $middleware = []
    ): void {
        list($controller, $controllerMethod) = explode("@", $action);

        $this->routes[] = [
            "method" => $method,
            "uri" => $uri,
            "controller" => $controller,
            "controllerMethod" => $controllerMethod,
            "middleware" => $middleware,
        ];
    }

    public function get(
        string $uri,
        string $controller,
        array $middleware = []
    ): void {
        $this->registerRoute("GET", $uri, $controller, $middleware);
    }

    public function post(
        string $uri,
        string $controller,
        array $middleware = []
    ): void {
        $this->registerRoute("POST", $uri, $controller, $middleware);
    }

    public function put(
        string $uri,
        string $controller,
        array $middleware = []
    ): void {
        $this->registerRoute("PUT", $uri, $controller, $middleware);
    }

    public function delete(
        string $uri,
        string $controller,
        array $middleware = []
    ): void {
        $this->registerRoute("DELETE", $uri, $controller, $middleware);
    }

    public function route(string $uri): void
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if ($requestMethod === "POST" && isset($_POST["_method"])) {
            $requestMethod = strtoupper($_POST["_method"]);
        }

        foreach ($this->routes as $route) {
            $uriSegments = explode("/", trim($uri, "/"));

            $routeSegments = explode("/", trim($route["uri"], "/"));

            $match = true;

            if (
                count($uriSegments) === count($routeSegments) &&
                strtoupper($route["method"]) === $requestMethod
            ) {
                $params = [];

                $match = true;

                for ($i = 0; $i < count($uriSegments); $i++) {
                    if (
                        $routeSegments[$i] !== $uriSegments[$i] &&
                        !preg_match("/\{(.+?)\}/", $routeSegments[$i])
                    ) {
                        $match = false;
                        break;
                    }

                    if (
                        preg_match("/\{(.+?)\}/", $routeSegments[$i], $matches)
                    ) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }

                if ($match) {
                    foreach ($route["middleware"] as $middleware) {
                        (new Authorize())->handle($middleware);
                    }
                    
                    $controller = "App\\Controllers\\" . $route["controller"];
                    $controllerMethod = $route["controllerMethod"];

                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            }
        }

        ErrorController::notFound();
    }
}
