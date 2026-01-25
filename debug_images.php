<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/classes/Database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = Database::getInstance();
$products = $db->fetchAll("SELECT id, name, image_url FROM products LIMIT 5");

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Name</th><th>Stored Path</th><th>Clean Path</th><th>File Exists on Disk?</th><th>Generated URL</th></tr>";

foreach ($products as $product) {
    $rawPath = $product['image_url'];

    // Remove domain if present to get relative path
    $cleanPath = str_replace([SITE_URL, 'https://honestchoicereview.com', 'http://honestchoicereview.com'], '', $rawPath);
    $cleanPath = ltrim($cleanPath, '/');

    // Check file existence
    $filePath = __DIR__ . '/' . $cleanPath;
    $fileExists = file_exists($filePath) ? "<b style='color:green'>YES</b>" : "<b style='color:red'>NO (" . realpath(dirname($filePath)) . "/" . basename($filePath) . ")</b>";

    $fullUrl = baseUrl($cleanPath);

    echo "<tr>";
    echo "<td>" . $product['id'] . "</td>";
    echo "<td>" . htmlspecialchars($product['name']) . "</td>";
    echo "<td>" . htmlspecialchars($rawPath) . "</td>";
    echo "<td>" . htmlspecialchars($cleanPath) . "</td>";
    echo "<td>" . $fileExists . "</td>";
    echo "<td><a href='$fullUrl' target='_blank'>Link</a></td>";
    echo "</tr>";
}
echo "</table>";
?>