<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        DB_OPTIONS
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$categories = [
    ['name' => 'Website', 'slug' => 'website', 'icon' => 'web'],
    ['name' => 'Plugins', 'slug' => 'plugins', 'icon' => 'extension'],
    ['name' => 'Addons', 'slug' => 'addons', 'icon' => 'add_box'],
    ['name' => 'Templates', 'slug' => 'templates', 'icon' => 'dashboard']
];

foreach ($categories as $cat) {
    // Check if exists
    $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
    $stmt->execute([$cat['slug']]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $db->prepare("INSERT INTO categories (name, slug, description, image_url, status, display_order) VALUES (?, ?, '', '', 'active', 0)");
        if ($stmt->execute([$cat['name'], $cat['slug']])) {
            echo "Added category: " . $cat['name'] . "\n";
        } else {
            echo "Failed to add category: " . $cat['name'] . "\n";
        }
    } else {
        echo "Category already exists: " . $cat['name'] . "\n";
    }
}
echo "Done.\n";
