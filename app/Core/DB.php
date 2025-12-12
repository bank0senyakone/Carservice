<?php

namespace App\Core;

/**
 * Database Connection Class (Singleton Pattern)
 * Provides a single PDO instance throughout the application
 */
class DB
{
    private static ?DB $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            $this->pdo = new \PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get the singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection
     */
    public function getConnection(): \PDO
    {
        return $this->pdo;
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
