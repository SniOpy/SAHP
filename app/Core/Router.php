<?php

class Router
{
    private array $routes = [
        '' => ['HomeController', 'index'],
        'services' => ['ServiceController', 'index'],
        'assainissement' => ['ServiceController', 'assainissement'],
        'curage' => ['ServiceController', 'curage'],
    ];

    public function dispatch(string $url): void
    {
        $url = trim($url, '/');

        if (!isset($this->routes[$url])) {
            $controller = new ErrorController();
            $controller->notFound();
            return;
        }

        [$controllerName, $method] = $this->routes[$url];

        require_once __DIR__ . "/../Controllers/{$controllerName}.php";

        $controller = new $controllerName();
        $controller->$method();
    }
}
