<?php

/**
 * Fix Admin Password Script
 * Updates the admin password to 'password123'
 */
header('Content-Type: text/plain');

$config = require __DIR__ . '/../config/database.php';

try {
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);

    // Generate correct hash for 'password123'
    $newHash = password_hash('password123', PASSWORD_DEFAULT);

    // Update admin and staff1 users
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username IN ('admin', 'staff1')");
    $stmt->execute([$newHash]);

    $affected = $stmt->rowCount();

    echo "=== Password Fix Complete ===\n\n";
    echo "Updated $affected user(s) with new password hash.\n\n";
    echo "Login credentials:\n";
    echo "  Username: admin\n";
    echo "  Password: password123\n\n";
    echo "You can now login at: http://localhost/car1/public/login\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
