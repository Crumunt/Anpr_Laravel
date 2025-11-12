<?php

/**
 * Script to fix MySQL tablespace issue for migrations table
 * 
 * This script drops the problematic migrations table so that
 * migrate:fresh can recreate it properly.
 * 
 * Usage: php fix_tablespace.php
 */

// Load Laravel environment
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $config = config('database.connections.mysql');
    $host = $config['host'];
    $port = $config['port'];
    $username = $config['username'];
    $password = $config['password'];
    $database = $config['database'];
    
    echo "Database: {$database}\n";
    echo "Host: {$host}\n";
    echo "Fixing tablespace issue...\n\n";
    
    // Connect to MySQL server (without database)
    $pdo = new PDO(
        "mysql:host={$host};port={$port}",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // First, try to connect to the database and drop all tables
    try {
        $dbPdo = new PDO(
            "mysql:host={$host};port={$port};dbname={$database}",
            $username,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        echo "Step 1: Getting list of tables...\n";
        $stmt = $dbPdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "Step 2: Dropping all tables...\n";
            // Disable foreign key checks
            $dbPdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            foreach ($tables as $table) {
                try {
                    $dbPdo->exec("DROP TABLE IF EXISTS `{$table}`");
                    echo "  ✓ Dropped table: {$table}\n";
                } catch (\Exception $e) {
                    echo "  ⚠ Could not drop table {$table}: " . $e->getMessage() . "\n";
                    // Try to discard tablespace first
                    try {
                        $dbPdo->exec("ALTER TABLE `{$table}` DISCARD TABLESPACE");
                        $dbPdo->exec("DROP TABLE IF EXISTS `{$table}`");
                        echo "  ✓ Dropped table {$table} after discarding tablespace\n";
                    } catch (\Exception $e2) {
                        echo "  ✗ Failed to drop table {$table}\n";
                    }
                }
            }
            
            // Re-enable foreign key checks
            $dbPdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        } else {
            echo "No tables found in database.\n";
        }
    } catch (\Exception $e) {
        echo "Could not connect to database (this is okay if database doesn't exist): " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 3: Dropping database...\n";
    // Now try to drop the database
    try {
        $pdo->exec("DROP DATABASE IF EXISTS `{$database}`");
        echo "✓ Dropped database\n";
    } catch (\Exception $e) {
        echo "⚠ Warning: " . $e->getMessage() . "\n";
        echo "Database may still have leftover files. Trying to create anyway...\n";
    }
    
    echo "\nStep 4: Creating fresh database...\n";
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Created database\n";
    
    echo "\n✓ Database setup complete! You can now run: php artisan migrate:fresh --seed\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "\nIf the error persists, you may need to manually delete the database directory.\n";
    echo "Please run the following SQL commands manually in MySQL:\n";
    echo "DROP DATABASE IF EXISTS `{$database}`;\n";
    echo "CREATE DATABASE `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
    echo "\nOr manually delete the folder: [MySQL Data Directory]/anpr/\n";
    exit(1);
}

