<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected array $routes = [];

    public function registerRoute(
        string $method,
        string $uri,
        string $action
    ): void {
        list($controller, $controllerMethod) = explode("@", $action);

        $this->routes[] = [
            "method" => $method,
            "uri" => $uri,
            "controller" => $controller,
            "controllerMethod" => $controllerMethod,
        ];
    }

    public function get(string $uri, string $controller): void
    {
        $this->registerRoute("GET", $uri, $controller);
    }

    public function post(string $uri, string $controller): void
    {
        $this->registerRoute("POST", $uri, $controller);
    }

    public function put(string $uri, string $controller): void
    {
        $this->registerRoute("PUT", $uri, $controller);
    }

    public function delete(string $uri, string $controller): void
    {
        $this->registerRoute("DELET", $uri, $controller);
    }

    public function route(string $uri): void
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

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
