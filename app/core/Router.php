<?php
declare(strict_types=1);

namespace app\core;

use RuntimeException;

class Router {
    private array $routes = [];

    /**
     * Registers a new route.
     */
    public function add(string $method, string $path, string $handler): void {
        $this->routes[strtoupper($method)][$this->normalizePath($path)] = $handler;
    }

    /**
     * Dispatches the current request.
     */
    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $uri = $this->normalizePath($_GET['url'] ?? '');

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            if ($this->matchRoute($route, $uri, $params)) {
                $this->executeHandler($handler, $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    /**
     * Normalize path by trimming slashes.
     */
    private function normalizePath(string $path): string {
        return trim(filter_var($path, FILTER_SANITIZE_URL), '/');
    }

    /**
     * Matches a route pattern to the request URI.
     */
    private function matchRoute(string $route, string $uri, ?array &$params): bool {
        $pattern = '#^' . preg_replace('/\{(\w+)}/', '(?P<$1>[^/]+)', $route) . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }
        return false;
    }

    /**
     * Executes the given handler.
     */
    private function executeHandler(string $handler, array $params): void {
        [$controller, $action] = explode('@', $handler);
        $controller = "app\\controllers\\{$controller}";

        if (!class_exists($controller) || !method_exists($controller, $action)) {
            throw new RuntimeException("Invalid route handler: {$handler}");
        }

        (new $controller())->$action($params);
    }
}
