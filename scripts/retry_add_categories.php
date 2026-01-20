<?php
// scripts/retry_add_categories.php

// Manually define creds from config/database.php to allow overriding host
define('DB_HOST', '127.0.0.1'); // Force TCP connection
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
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "Connected successfully.\n";

} catch (PDOException $e) {
    echo "Connection failed with 127.0.0.1. Trying localhost...\n";
    try {
        // Fallback to localhost
        $dsn = "mysql:host=localhost;dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $db = new PDO($dsn, DB_USER, DB_PASS, $options);
        echo "Connected successfully via localhost.\n";
    } catch (PDOException $e2) {
        die("FATAL: Could not connect to database. Error: " . $e2->getMessage() . "\n");
    }
}

$categories = [
    ['name' => 'Website', 'slug' => 'website'],
    ['name' => 'Plugins', 'slug' => 'plugins'],
    ['name' => 'Addons', 'slug' => 'addons'],
    ['name' => 'Templates', 'slug' => 'templates']
];

foreach ($categories as $cat) {
    // Check if exists
    $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
    $stmt->execute([$cat['slug']]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $db->prepare("INSERT INTO categories (name, slug, description, image_url, status, display_order) VALUES (?, ?, '', '', 'active', 0)");
        if ($stmt->execute([$cat['name'], $cat['slug']])) {
            echo "SUCCESS: Added category '" . $cat['name'] . "'\n";
        } else {
            echo "ERROR: Failed to add category '" . $cat['name'] . "'\n";
        }
    } else {
        echo "INFO: Category '" . $cat['name'] . "' already exists.\n";
    }
}
echo "Script finished.\n";
