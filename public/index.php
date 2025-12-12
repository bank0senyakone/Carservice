<?php

/**
 * Front Controller - Entry Point
 * Workshop Management Portal
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $prefix = 'App\\';
    $baseDir = BASE_PATH . '/app/';

    // Check if class uses our namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get relative class name
    $relativeClass = substr($class, $len);

    // Replace namespace separators with directory separators
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;

// Initialize router
$router = new Router();

// ============================================
// Public Routes (No Authentication Required)
// ============================================
$router->get('/', 'AuthController', 'login');
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'authenticate');
$router->get('/logout', 'AuthController', 'logout');

// ============================================
// Protected Routes (Authentication Required)
// ============================================

// Dashboard
$router->get('/admin/dashboard', 'DashboardController', 'index');

// Jobs
$router->get('/admin/jobs', 'JobController', 'index');
$router->get('/admin/jobs/create', 'JobController', 'create');
$router->post('/admin/jobs/store', 'JobController', 'store');
$router->get('/admin/jobs/{id}', 'JobController', 'show');
$router->get('/admin/jobs/{id}/edit', 'JobController', 'edit');
$router->post('/admin/jobs/{id}/update', 'JobController', 'update');
$router->post('/admin/jobs/{id}/delete', 'JobController', 'delete');
$router->post('/admin/jobs/{id}/status', 'JobController', 'updateStatus');

// Inventory
$router->get('/admin/inventory', 'InventoryController', 'index');
$router->get('/admin/inventory/create', 'InventoryController', 'create');
$router->post('/admin/inventory/store', 'InventoryController', 'store');
$router->get('/admin/inventory/{id}', 'InventoryController', 'show');
$router->get('/admin/inventory/{id}/edit', 'InventoryController', 'edit');
$router->post('/admin/inventory/{id}/update', 'InventoryController', 'update');
$router->post('/admin/inventory/{id}/delete', 'InventoryController', 'delete');

// Customers
$router->get('/admin/customers', 'CustomerController', 'index');
$router->get('/admin/customers/create', 'CustomerController', 'create');
$router->post('/admin/customers/store', 'CustomerController', 'store');
$router->get('/admin/customers/{id}', 'CustomerController', 'show');
$router->get('/admin/customers/{id}/edit', 'CustomerController', 'edit');
$router->post('/admin/customers/{id}/update', 'CustomerController', 'update');
$router->post('/admin/customers/{id}/delete', 'CustomerController', 'delete');

// Quotes/Invoices
$router->get('/admin/quotes', 'QuoteController', 'index');
$router->get('/admin/quotes/{id}', 'QuoteController', 'show');
$router->post('/admin/quotes/{id}/approve', 'QuoteController', 'approve');

// Dispatch the request
$router->dispatch();
