<?php

namespace App\Core;

/**
 * Router Class
 * Handles URL routing and dispatching to controllers
 */
class Router
{
    private array $routes = [];
    private array $protectedPrefixes = ['/admin', '/inventory', '/jobs', '/customers', '/quotes'];

    /**
     * Register a GET route
     */
    public function get(string $path, string $controller, string $action): self
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
        return $this;
    }

    /**
     * Register a POST route
     */
    public function post(string $path, string $controller, string $action): self
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
        return $this;
    }

    /**
     * Check if the current route requires authentication
     */
    private function requiresAuth(string $uri): bool
    {
        foreach ($this->protectedPrefixes as $prefix) {
            if (strpos($uri, $prefix) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user is authenticated
     */
    private function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Dispatch the request to the appropriate controller
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path if running in subdirectory
        $basePath = '/car1/public';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Ensure URI starts with /
        if (empty($uri) || $uri === '') {
            $uri = '/';
        }

        // Check authentication for protected routes
        if ($this->requiresAuth($uri) && !$this->isAuthenticated()) {
            header('Location: /car1/public/login');
            exit;
        }

        // Try to find exact match first
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $this->callAction($route['controller'], $route['action']);
            return;
        }

        // Try to match routes with parameters
        foreach ($this->routes[$method] ?? [] as $path => $route) {
            $pattern = $this->convertToRegex($path);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $this->callAction($route['controller'], $route['action'], $matches);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 - Page Not Found";
    }

    /**
     * Convert route path to regex pattern
     */
    private function convertToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Call the controller action
     */
    private function callAction(string $controller, string $action, array $params = []): void
    {
        $controllerClass = "App\\Controllers\\{$controller}";

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller not found: {$controllerClass}");
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception("Action not found: {$action}");
        }

        call_user_func_array([$controllerInstance, $action], $params);
    }
}
