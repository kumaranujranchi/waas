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

echo "<h1>Debug Image URLs</h1>";
echo "<p>SITE_URL: " . SITE_URL . "</p>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Name</th><th>Raw Image URL</th><th>Fixed URL (Preview)</th></tr>";

foreach ($products as $product) {
    $rawUrl = $product['image_url'];
    $fixedUrl = str_replace(['https://honestchoicereview.com', 'http://honestchoicereview.com'], SITE_URL, $rawUrl);

    if (!filter_var($fixedUrl, FILTER_VALIDATE_URL)) {
        $fixedUrl = baseUrl($fixedUrl);
    }

    echo "<tr>";
    echo "<td>" . $product['id'] . "</td>";
    echo "<td>" . $product['name'] . "</td>";
    echo "<td>" . htmlspecialchars($rawUrl) . "</td>";
    echo "<td>" . htmlspecialchars($fixedUrl) . "<br><a href='$fixedUrl' target='_blank'>Link</a></td>";
    echo "</tr>";
}
echo "</table>";
?>