<?php

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable|array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put(string $uri, callable|array $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete(string $uri, callable|array $action): void
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute(
        string $method,
        string $uri,
        callable|array $action
    ): void {

        $uri = '/' . trim($uri, '/');

        $this->routes[$method][$uri] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = parse_url(
            $_SERVER['REQUEST_URI'],
            PHP_URL_PATH
        );

        $basePath = dirname($_SERVER['SCRIPT_NAME']);

        if ($basePath !== '/') {
            $uri = str_replace($basePath, '', $uri);
        }

        $uri = '/' . trim($uri, '/');

        if ($uri === '//') {
            $uri = '/';
        }

        if (!isset($this->routes[$method])) {
            $this->notFound();
        }

        foreach ($this->routes[$method] as $route => $action) {

            $pattern = preg_replace(
                '/\{([a-zA-Z0-9_]+)\}/',
                '([^/]+)',
                $route
            );

            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches);

                $this->execute(
                    $action,
                    $matches
                );

                return;
            }
        }

        $this->notFound();
    }

    private function execute(
        callable|array $action,
        array $parameters = []
    ): void {

        if (is_callable($action)) {

            call_user_func_array(
                $action,
                $parameters
            );

            return;
        }

        [$controller, $method] = $action;

        if (!class_exists($controller)) {
            throw new Exception(
                "{$controller} not found."
            );
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new Exception(
                "{$method} not found."
            );
        }

        call_user_func_array(
            [$instance, $method],
            $parameters
        );
    }

    private function notFound(): void
    {
        http_response_code(404);

        require BASE_PATH . '/views/errors/404.php';

        exit;
    }

    public function redirect(
        string $url,
        int $status = 302
    ): never {

        header(
            "Location: {$url}",
            true,
            $status
        );

        exit;
    }
}