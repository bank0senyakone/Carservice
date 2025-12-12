<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuthModel;

/**
 * Auth Controller
 * Handles user authentication (login, logout)
 */
class AuthController extends Controller
{
    private AuthModel $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    /**
     * Display login form
     */
    public function login(): void
    {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('/car1/public/admin/dashboard');
            return;
        }

        $flash = $this->getFlash();
        $this->viewOnly('auth/login', [
            'error' => ($flash && $flash['type'] === 'error') ? $flash['message'] : null
        ]);
    }

    /**
     * Process login attempt
     */
    public function authenticate(): void
    {
        $username = trim($this->input('username', ''));
        $password = $this->input('password', '');

        // Validate input
        if (empty($username) || empty($password)) {
            $this->setFlash('error', 'Please enter both username and password');
            $this->redirect('/car1/public/login');
            return;
        }

        // Find user
        $user = $this->authModel->getUserByUsername($username);

        if (!$user) {
            $this->setFlash('error', 'Invalid username or password');
            $this->redirect('/car1/public/login');
            return;
        }

        // Verify password
        if (!$this->authModel->verifyPassword($user['password'], $password)) {
            $this->setFlash('error', 'Invalid username or password');
            $this->redirect('/car1/public/login');
            return;
        }

        // Login successful - create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        $this->redirect('/car1/public/admin/dashboard');
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        // Destroy session
        session_unset();
        session_destroy();

        // Redirect to login
        $this->redirect('/car1/public/login');
    }
}
