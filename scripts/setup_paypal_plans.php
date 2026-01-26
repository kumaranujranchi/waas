<?php
/**
 * Setup PayPal Plans Script
 * Run this to generate PayPal Plans for all products and update the database.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/Database.php';

echo "<h1>PayPal Plan Setup Tool</h1>";

// 1. Verify Credentials
$clientId = defined('PAYPAL_CLIENT_ID') ? PAYPAL_CLIENT_ID : '';
$secret = defined('PAYPAL_SECRET') ? PAYPAL_SECRET : '';

if (empty($clientId) || empty($secret)) {
    die("❌ PayPal credentials are missing in config.php");
}

echo "<p>✅ Credentials Found. Testing connection...</p>";

// 2. Test Connection
$token = getPayPalAccessToken();
if (isset($token['error'])) {
    die("❌ Connection Failed: " . $token['error'] . "<br>Check your Client ID and Secret.");
}
echo "<p>✅ Connection Successful (Token acquired)</p>";

// 3. Fetch Local Plans
$db = Database::getInstance();
$plans = $db->fetchAll("SELECT p.*, pr.name as product_name FROM pricing_plans p JOIN products pr ON p.product_id = pr.id");

if (empty($plans)) {
    die("❌ No pricing plans found in database.");
}

echo "<h3>Found " . count($plans) . " plans. Creating PayPal counterparts...</h3>";
echo "<ul>";

foreach ($plans as $plan) {
    echo "<li><strong>" . htmlspecialchars($plan['product_name']) . " - " . htmlspecialchars($plan['plan_name']) . "</strong><br>";

    // Convert price to USD (Approximate if not USD)
    $currency = 'USD';
    $price = $plan['price'];

    // Simple conversion if INR
    if (defined('CURRENCY') && CURRENCY === 'INR') {
        $price = $price / 86; // Current approx rate
        $price = number_format($price, 2, '.', '');
    }

    // Create Product in PayPal (Required for Plan)
    $ppProduct = createPayPalProduct(
        $plan['product_name'],
        "Subscription for " . $plan['product_name'],
        'SERVICE',
        'SOFTWARE'
    );

    if (isset($ppProduct['error'])) {
        echo "❌ Failed to create product: " . $ppProduct['error'];
        continue;
    }

    $ppProductId = $ppProduct['id'];
    echo "Creating Plan for Product ID: $ppProductId (Price: \$$price)<br>";

    // Calculate Interval
    $intervalCount = 1;
    $intervalUnit = 'MONTH';

    if ($plan['plan_type'] === 'yearly') {
        $intervalCount = 1;
        $intervalUnit = 'YEAR';
    } elseif ($plan['plan_type'] === 'semi_annual') {
        $intervalCount = 6;
        $intervalUnit = 'MONTH';
    }

    // Create Plan
    $ppPlan = createPayPalPlan($ppProductId, $plan['product_name'] . ' - ' . $plan['plan_name'], $price, $intervalCount, $intervalUnit);

    if (isset($ppPlan['error'])) {
        echo "❌ Failed to create plan: " . ($ppPlan['error']);
    } else {
        $newPlanId = $ppPlan['id'];

        // Update Database
        $db->query(
            "UPDATE pricing_plans SET paypal_plan_id = ? WHERE id = ?",
            [$newPlanId, $plan['id']]
        );

        echo "✅ <span style='color:green'>Success! New PayPal Plan ID: $newPlanId</span>";
    }
    echo "</li><hr>";
    flush();
}

echo "</ul>";
echo "<h2>🎉 Setup Complete. You can now test checkout.</h2>";
