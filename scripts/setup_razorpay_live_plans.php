<?php
/**
 * Razorpay Live Plan Migration Script
 * -----------------------------------
 * This script will:
 * 1. Connect to the database
 * 2. Fetch all active pricing plans
 * 3. Create these plans on Razorpay (using LIVE keys from config)
 * 4. Update the local database with the new Razorpay Plan IDs
 *
 * Usage: php scripts/setup_razorpay_live_plans.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';

// Ensure we are in CLI
// if (php_sapi_name() !== 'cli') {
//     die("This script can only be run from the command line.\n");
// }

// Simple HTML styling
echo '<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Live Migration</title>
    <style>
        body { font-family: monospace; background: #1a1a1a; color: #00ff00; padding: 20px; line-height: 1.5; }
        .error { color: #ff5555; }
        .success { color: #55ff55; }
        .info { color: #55ffff; }
        hr { border: 0; border-top: 1px solid #333; margin: 10px 0; }
    </style>
</head>
<body>
<h3>Starting Razorpay Live Plan Migration...</h3>';

echo "Using Key ID: " . substr('rzp_live_RwX28Qv2VotZir', 0, 15) . "...<br><br>";

// Function to create plan on Razorpay
function createRazorpayPlan($name, $amount, $currency = 'INR', $period = 'monthly', $interval = 1)
{
    $apiKey = 'rzp_live_RwX28Qv2VotZir';
    $apiSecret = '0OEb6iiMFr84RUcA90wXZVYY';

    $url = 'https://api.razorpay.com/v1/plans';

    $data = [
        'period' => $period,
        'interval' => $interval,
        'item' => [
            'name' => $name,
            'amount' => $amount * 100, // Amount in paise
            'currency' => $currency,
            'description' => "Subscription for " . $name
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':' . $apiSecret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }
    curl_close($ch);

    $response = json_decode($result, true);

    if ($httpCode !== 200) {
        return ['error' => $response['error']['description'] ?? 'Unknown error'];
    }

    return $response;
}

try {
    $db = Database::getInstance();

    // Fetch all pricing plans joined with product names for better plan naming
    $sql = "SELECT pp.*, p.name as product_name 
            FROM pricing_plans pp 
            JOIN products p ON pp.product_id = p.id 
            WHERE pp.status = 'active'";

    $plans = $db->fetchAll($sql);

    echo "Found " . count($plans) . " active plans to sync.<br><br>";

    foreach ($plans as $plan) {
        echo "<div class='info'>Processing: " . $plan['product_name'] . " - " . $plan['plan_name'] . "...</div>";

        // Determine period and interval
        $period = 'monthly';
        $interval = 1;

        // Map our billing cycles to Razorpay format
        if ($plan['plan_type'] === 'monthly') {
            $period = 'monthly';
            $interval = 1;
        } elseif ($plan['plan_type'] === 'semi_annual') {
            $period = 'monthly'; // Razorpay allows monthly period with interval
            $interval = 6;
        } elseif ($plan['plan_type'] === 'yearly') {
            $period = 'yearly';
            $interval = 1;
        }

        $planName = $plan['product_name'] . ' - ' . $plan['plan_name'];

        // Create on Razorpay
        $rzpPlan = createRazorpayPlan($planName, $plan['price'], 'INR', $period, $interval);

        if (isset($rzpPlan['id'])) {
            echo "<span class='success'>  -> Created on Razorpay: " . $rzpPlan['id'] . "</span><br>";

            // Update Database
            $updateSql = "UPDATE pricing_plans SET razorpay_plan_id = ? WHERE id = ?";
            $db->query($updateSql, [$rzpPlan['id'], $plan['id']]);
            echo "<span class='success'>  -> Database updated.</span><br>";
        } else {
            echo "<span class='error'>  -> ERROR: " . ($rzpPlan['error'] ?? 'Unknown error') . "</span><br>";
        }

        echo "<hr>";
        flush(); // Flush output to browser
    }

    echo "<h3>Migration Completed!</h3>";
    echo "<p>Please delete this file from your server now.</p>";
    echo "</body></html>";

} catch (Exception $e) {
    echo "<h3 class='error'>Critical Error: " . $e->getMessage() . "</h3></body></html>";
}
