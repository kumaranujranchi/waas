<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

echo "<h1>Database Schema Debug</h1>";

// Check products table columns
echo "<h2>Table: products</h2>";
try {
    $stmt = $conn->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        $bg = ($col['Field'] == 'faqs') ? 'style="background:yellow"' : '';
        echo "<tr $bg>";
        foreach ($col as $val) {
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    // Check if 'faqs' column exists
    $hasFaqs = false;
    foreach ($columns as $col) {
        if ($col['Field'] == 'faqs') {
            $hasFaqs = true;
            break;
        }
    }

    if ($hasFaqs) {
        echo "<h3 style='color:green'>✅ 'faqs' column FOUND.</h3>";
    } else {
        echo "<h3 style='color:red'>❌ 'faqs' column MISSING.</h3>";
        echo "<p>Please run: <code>ALTER TABLE products ADD COLUMN faqs JSON;</code></p>";
    }

} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}

// Dump one product to see what's in 'faqs'
echo "<h2>First 5 Products Data</h2>";
try {
    $stmt = $conn->query("SELECT id, name, faqs FROM products LIMIT 5");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>FAQs Column (Raw)</th></tr>";
    foreach ($products as $p) {
        echo "<tr>";
        echo "<td>" . $p['id'] . "</td>";
        echo "<td>" . htmlspecialchars($p['name']) . "</td>";
        echo "<td>" . htmlspecialchars($p['faqs']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<p>Error fetching products.</p>";
}

echo "<hr>";
echo "<h2>Server Error Log (Last 50 Lines)</h2>";

$logFiles = [
    __DIR__ . '/error_log',
    __DIR__ . '/public_html/error_log',
    '/var/log/apache2/error.log',
    ini_get('error_log')
];

$foundLog = false;
foreach ($logFiles as $file) {
    if (!empty($file) && file_exists($file) && is_readable($file)) {
        echo "<h3>Log File: $file</h3>";
        $foundLog = true;
        
        $lines = file($file);
        $tail = array_slice($lines, -50);
        $content = implode("", $tail);
        
        echo "<pre style='background:#111; color:#0f0; padding:10px; overflow:auto;'>";
        echo htmlspecialchars($content);
        echo "</pre>";
    }
}

if (!$foundLog) {
    echo "<p style='color:red'>No readable error_log file found in standard paths.</p>";
    echo "<p>Current PHP error_log path: " . htmlspecialchars(ini_get('error_log')) . "</p>";
}

