<?php

/**
 * Database Diagnostic Script
 * Check database connection and users table
 */
header('Content-Type: text/plain');
echo "=== Database Diagnostic ===\n\n";

// Load config
$config = require __DIR__ . '/../config/database.php';
echo "Config loaded:\n";
echo "  Host: {$config['host']}\n";
echo "  Database: {$config['dbname']}\n";
echo "  Username: {$config['username']}\n";
echo "  Password: " . (empty($config['password']) ? '(empty)' : '(set)') . "\n\n";

// Try to connect
try {
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    echo "✓ Database connection successful!\n\n";

    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Users table exists!\n\n";

        // List all users
        $stmt = $pdo->query("SELECT id, username, name, role, LEFT(password, 20) as pwd_preview FROM users");
        $users = $stmt->fetchAll();

        echo "Users in database (" . count($users) . "):\n";
        foreach ($users as $user) {
            echo "  - ID: {$user['id']}, Username: {$user['username']}, Name: {$user['name']}, Role: {$user['role']}\n";
            echo "    Password hash starts with: {$user['pwd_preview']}...\n";
        }

        if (empty($users)) {
            echo "  (No users found - you need to run the seed.sql file)\n";
        }

        // Test password verification for admin user
        $stmt = $pdo->prepare("SELECT password FROM users WHERE username = 'admin'");
        $stmt->execute();
        $admin = $stmt->fetch();

        if ($admin) {
            echo "\nTesting password 'password123' for admin user:\n";
            $result = password_verify('password123', $admin['password']);
            echo "  Result: " . ($result ? "✓ Password MATCHES!" : "✗ Password does NOT match") . "\n";

            if (!$result) {
                echo "\n  The stored hash doesn't match 'password123'.\n";
                echo "  Expected hash format: \$2y\$10\$...\n";
                echo "  Actual hash: " . substr($admin['password'], 0, 30) . "...\n";
            }
        } else {
            echo "\n✗ Admin user not found!\n";
            echo "  You need to run the seed.sql file to create the admin user.\n";
        }
    } else {
        echo "✗ Users table does NOT exist!\n";
        echo "  You need to run the schema.sql file first.\n";
    }
} catch (PDOException $e) {
    echo "✗ Database connection FAILED!\n";
    echo "  Error: " . $e->getMessage() . "\n\n";

    echo "Please check:\n";
    echo "1. Is Laragon's MySQL service running?\n";
    echo "2. Does the database 'wms_db' exist?\n";
    echo "3. Is the password correct in config/database.php?\n";
}

echo "\n=== End Diagnostic ===\n";
