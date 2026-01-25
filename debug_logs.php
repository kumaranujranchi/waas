<?php
// Simple Log Viewer
require_once __DIR__ . '/config/config.php'; // For auth check if possible, but keep simple for now

// Files to check
$logFiles = [
    __DIR__ . '/error_log',
    __DIR__ . '/public_html/error_log',
    __DIR__ . '/admin/products/error_log',
    '/var/log/apache2/error.log', // Common system paths (unlikely readable but try)
];

echo "<h1>Debug Log Viewer</h1>";
echo "<p>Looking for logs...</p>";

$found = false;

foreach ($logFiles as $file) {
    if (file_exists($file) && is_readable($file)) {
        echo "<h2>Found: " . htmlspecialchars($file) . "</h2>";
        $found = true;

        // Read last 100 lines
        $lines = file($file);
        $tail = array_slice($lines, -50);
        $content = implode("", $tail);

        echo "<pre style='background:#f4f4f4; padding:10px; border:1px solid #ccc; overflow:auto;'>";
        echo htmlspecialchars($content);
        echo "</pre>";
    }
}

if (!$found) {
    echo "<h3 style='color:red;'>No standard 'error_log' file found.</h3>";
    echo "<p>Please check your Hosting Control Panel (cPanel) -> File Manager.</p>";
}
