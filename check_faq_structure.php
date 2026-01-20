<?php
/**
 * Check product_faqs table structure
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$db = new Database();

echo "<h1>Product FAQs Table Structure</h1>";

// Show table structure
echo "<h2>Table Schema</h2>";
$structure = $db->fetchAll("SHOW CREATE TABLE product_faqs");
echo "<pre>";
print_r($structure);
echo "</pre>";

// Show indexes
echo "<h2>Table Indexes</h2>";
$indexes = $db->fetchAll("SHOW INDEX FROM product_faqs");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Key Name</th><th>Column</th><th>Unique</th><th>Type</th></tr>";
foreach ($indexes as $index) {
    echo "<tr>";
    echo "<td>{$index['Key_name']}</td>";
    echo "<td>{$index['Column_name']}</td>";
    echo "<td>" . ($index['Non_unique'] == 0 ? 'YES' : 'NO') . "</td>";
    echo "<td>{$index['Index_type']}</td>";
    echo "</tr>";
}
echo "</table>";

// Check for duplicate questions across products
echo "<h2>Duplicate Questions Across Products</h2>";
$duplicates = $db->fetchAll("
    SELECT question, GROUP_CONCAT(product_id) as product_ids, COUNT(*) as count
    FROM product_faqs
    GROUP BY question
    HAVING count > 1
");

if (!empty($duplicates)) {
    echo "<p style='color: orange;'><strong>Found questions used in multiple products:</strong></p>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Question</th><th>Product IDs</th><th>Count</th></tr>";
    foreach ($duplicates as $dup) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($dup['question']) . "</td>";
        echo "<td>{$dup['product_ids']}</td>";
        echo "<td>{$dup['count']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: green;'>No duplicate questions found across products.</p>";
}
?>