<?php

class Router {
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void {
        $this->routes[] = [
            'method' => 'GET',
            'path'   => $path,
            'controller' => $controller,
            'action' => $method,
        ];
    }

    public function post(string $path, string $controller, string $method): void {
        $this->routes[] = [
            'method' => 'POST',
            'path'   => $path,
            'controller' => $controller,
            'action' => $method,
        ];
    }

    public function dispatch(): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                $controllerClass = $route['controller'];
                $action          = $route['action'];
                $controller      = new $controllerClass();
                $controller->$action();
                return;
            }
        }

        http_response_code(404);
        echo "<h1>404 – Page Not Found</h1>";
    }
}
