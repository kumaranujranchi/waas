<?php
/**
 * Debug Script - Check Product FAQs
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = new Database();

// Get all products with FAQ count
$sql = "SELECT 
    p.id,
    p.name,
    COUNT(f.id) as faq_count
FROM products p
LEFT JOIN product_faqs f ON p.id = f.product_id
GROUP BY p.id
ORDER BY p.id";

$products = $db->fetchAll($sql);

echo "<h1>Product FAQ Debug Report</h1>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Product ID</th><th>Product Name</th><th>FAQ Count</th><th>Details</th></tr>";

foreach ($products as $product) {
    echo "<tr>";
    echo "<td>{$product['id']}</td>";
    echo "<td>{$product['name']}</td>";
    echo "<td><strong>{$product['faq_count']}</strong></td>";

    // Get actual FAQs
    $faqs = $db->fetchAll("SELECT * FROM product_faqs WHERE product_id = ? ORDER BY display_order", [$product['id']]);
    echo "<td>";
    if (!empty($faqs)) {
        echo "<ul>";
        foreach ($faqs as $faq) {
            echo "<li><strong>Q:</strong> " . htmlspecialchars($faq['question']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<em>No FAQs</em>";
    }
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

// Check for any duplicate display_order issues
echo "<h2>Checking for Duplicate display_order Issues</h2>";
$duplicates = $db->fetchAll("
    SELECT product_id, display_order, COUNT(*) as count
    FROM product_faqs
    GROUP BY product_id, display_order
    HAVING count > 1
");

if (!empty($duplicates)) {
    echo "<p style='color: red;'><strong>WARNING: Found duplicate display_order values!</strong></p>";
    echo "<pre>" . print_r($duplicates, true) . "</pre>";
} else {
    echo "<p style='color: green;'>No duplicate display_order issues found.</p>";
}

// Check table structure
echo "<h2>Table Structure</h2>";
$structure = $db->fetchAll("DESCRIBE product_faqs");
echo "<pre>" . print_r($structure, true) . "</pre>";
?>