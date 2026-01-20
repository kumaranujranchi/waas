<?php
// scripts/retry_create_consultations_table.php

// Manually define creds to match successful retry_add_categories.php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'u743570205_waas');
define('DB_USER', 'u743570205_waas');
define('DB_PASS', 'Anuj@2025@2026');
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    echo "Attempting connection to " . DB_HOST . "...\n";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "Connected successfully.\n";

} catch (PDOException $e) {
    echo "Connection failed with 127.0.0.1. Trying localhost...\n";
    try {
        $dsn = "mysql:host=localhost;dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        echo "Connected successfully via localhost.\n";
    } catch (PDOException $e2) {
        die("FATAL: Could not connect to database. Error: " . $e2->getMessage() . "\n");
    }
}

try {
    $sql = "CREATE TABLE IF NOT EXISTS consultations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        business_type VARCHAR(100) NOT NULL,
        requirements TEXT NULL,
        preferred_date DATE NULL,
        preferred_time TIME NULL,
        status ENUM('pending', 'contacted', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
        admin_notes TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX (user_id),
        INDEX (email),
        INDEX (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql);
    echo "Table 'consultations' created/checked successfully.\n";

} catch (PDOException $e) {
    die("Table Creation Error: " . $e->getMessage() . "\n");
}
