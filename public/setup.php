<?php

/**
 * Database Setup Script
 * Run this file once to create the database and tables
 */

// Display as plain text for easier reading
header('Content-Type: text/plain');

// Try different connection options for Laragon
$connectionOptions = [
    ['host' => 'localhost', 'port' => 3306],
    ['host' => '127.0.0.1', 'port' => 3306],
    ['host' => '127.0.0.1', 'port' => null],
];

$pdo = null;
$workingConfig = null;

foreach ($connectionOptions as $config) {
    try {
        $dsn = "mysql:host={$config['host']}";
        if ($config['port']) {
            $dsn .= ";port={$config['port']}";
        }

        $pdo = new PDO($dsn, 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $workingConfig = $config;
        echo "Connected successfully using host: {$config['host']}" . ($config['port'] ? ":{$config['port']}" : "") . "\n\n";
        break;
    } catch (PDOException $e) {
        echo "Failed with {$config['host']}: " . $e->getMessage() . "\n";
    }
}

if (!$pdo) {
    die("\nCould not connect to MySQL. Please check if:\n1. Laragon is running\n2. MySQL service is started\n3. Port 3306 is accessible\n");
}

try {
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS wms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database created successfully!\n";

    $pdo->exec("USE wms_db");

    // Read and execute schema
    $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
    $schema = preg_replace('/CREATE DATABASE.*?;/i', '', $schema);
    $schema = preg_replace('/USE.*?;/i', '', $schema);

    $statements = array_filter(array_map('trim', explode(';', $schema)));

    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                if (
                    strpos($e->getMessage(), 'already exists') === false &&
                    strpos($e->getMessage(), 'Duplicate') === false
                ) {
                    echo "Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    echo "Schema created successfully!\n";

    // Read and execute seed data
    $seed = file_get_contents(__DIR__ . '/../database/seed.sql');
    $seed = preg_replace('/USE.*?;/i', '', $seed);

    $statements = array_filter(array_map('trim', explode(';', $seed)));

    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate') === false) {
                    echo "Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    echo "Seed data inserted successfully!\n";

    echo "\n=================================\n";
    echo "Setup Complete!\n";
    echo "=================================\n";
    echo "Login credentials:\n";
    echo "  Username: admin\n";
    echo "  Password: password123\n";
    echo "\nVisit: http://localhost/car1/public/login\n";

    // Update config file with working settings
    if ($workingConfig) {
        echo "\nWorking database config:\n";
        echo "  Host: {$workingConfig['host']}\n";
        if ($workingConfig['port']) {
            echo "  Port: {$workingConfig['port']}\n";
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
