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
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

echo "Starting Razorpay Live Plan Migration...\n";
echo "Using Key ID: " . substr(RAZORPAY_KEY_ID, 0, 15) . "...\n";

// Function to create plan on Razorpay
function createRazorpayPlan($name, $amount, $currency = 'INR', $period = 'monthly', $interval = 1)
{
    $apiKey = RAZORPAY_KEY_ID;
    $apiSecret = RAZORPAY_KEY_SECRET;

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

    echo "Found " . count($plans) . " active plans to sync.\n\n";

    foreach ($plans as $plan) {
        echo "Processing: " . $plan['product_name'] . " - " . $plan['plan_name'] . "...\n";

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
            echo "  -> Created on Razorpay: " . $rzpPlan['id'] . "\n";

            // Update Database
            $updateSql = "UPDATE pricing_plans SET razorpay_plan_id = ? WHERE id = ?";
            $db->query($updateSql, [$rzpPlan['id'], $plan['id']]);
            echo "  -> Database updated.\n";
        } else {
            echo "  -> ERROR: " . ($rzpPlan['error'] ?? 'Unknown error') . "\n";
        }

        echo "--------------------------------------------------\n";
    }

    echo "\nMigration Completed!\n";

} catch (Exception $e) {
    echo "Critical Error: " . $e->getMessage() . "\n";
}
