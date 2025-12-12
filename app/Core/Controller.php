<?php

namespace App\Core;

/**
 * Base Controller Class
 * Provides common functionality for all controllers
 */
class Controller
{
    protected array $data = [];

    /**
     * Render a view with the main layout
     * 
     * @param string $view View path relative to views directory
     * @param array $data Data to pass to the view
     * @param string $layout Layout to use (default: main)
     */
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        // Extract data to make variables available in views
        extract($data);

        // Capture the view content
        ob_start();
        $viewPath = dirname(__DIR__, 2) . "/views/{$view}.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("View not found: {$view}");
        }
        $content = ob_get_clean();

        // Render within layout
        $layoutPath = dirname(__DIR__, 2) . "/views/layouts/{$layout}.php";
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    }

    /**
     * Render a view without layout (for auth pages, etc.)
     */
    protected function viewOnly(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = dirname(__DIR__, 2) . "/views/{$view}.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("View not found: {$view}");
        }
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get the current logged in user
     */
    protected function getCurrentUser(): ?array
    {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'] ?? '',
                'name' => $_SESSION['name'] ?? '',
                'role' => $_SESSION['role'] ?? ''
            ];
        }
        return null;
    }

    /**
     * Set a flash message
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get and clear flash message
     */
    protected function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Get POST data with optional default
     */
    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get query parameter with optional default
     */
    protected function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
}
