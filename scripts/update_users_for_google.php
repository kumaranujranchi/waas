<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    echo "Checking users table structure...\n";

    // Check if google_id exists
    $stmt = $conn->query("SHOW COLUMNS FROM users LIKE 'google_id'");
    $exists = $stmt->fetch();

    if (!$exists) {
        echo "Adding google_id column...\n";
        $sql = "ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL UNIQUE AFTER email";
        $conn->exec($sql);
        echo "google_id column added.\n";
    } else {
        echo "google_id column already exists.\n";
    }

    // Check if avatar exists
    $stmt = $conn->query("SHOW COLUMNS FROM users LIKE 'avatar'");
    $exists = $stmt->fetch();

    if (!$exists) {
        echo "Adding avatar column...\n";
        $sql = "ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER phone";
        $conn->exec($sql);
        echo "avatar column added.\n";
    } else {
        echo "avatar column already exists.\n";
    }

    echo "Database update completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
