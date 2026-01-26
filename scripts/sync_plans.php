<?php
/**
 * Sync Local Plans to Razorpay
 * 
 * This script fetches all plans from the local database,
 * creates them in Razorpay, and outputs the mapping array
 * for config.php.
 */

// Debug on
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';

// Razorpay Keys
$keyId = RAZORPAY_KEY_ID;
$keySecret = RAZORPAY_KEY_SECRET;

echo "<h3>Syncing Plans with Razorpay...</h3>";
echo "<pre>";

// 1. Fetch Plans from DB
$db = Database::getInstance();
$plans = $db->fetchAll("SELECT p.*, prod.name as product_name FROM pricing_plans p JOIN products prod ON p.product_id = prod.id");

$mapping = [];

foreach ($plans as $plan) {
    echo "Processing: " . $plan['product_name'] . " - " . $plan['plan_name'] . "...\n";

    // Convert price to paise (Razorpay expects integer)
    $amount = (int) ($plan['price'] * 100);
    $currency = 'INR';
    $period = $plan['plan_type']; // 'monthly', 'yearly' (Check DB values!)

    // Normalize period
    $period = strtolower($period);
    if ($period == '6 months' || $period == 'half-yearly') {
        $period = 'monthly';
        $interval = 6;
    } elseif ($period == 'yearly' || $period == 'annual') {
        $period = 'yearly';
        $interval = 1;
    } else {
        $period = 'monthly';
        $interval = 1;
    }

    $planData = [
        'period' => $period,
        'interval' => $interval,
        'item' => [
            'name' => substr($plan['product_name'] . ' - ' . $plan['plan_name'], 0, 40), // Limit name length
            'amount' => $amount,
            'currency' => $currency,
            'description' => "Plan ID: " . $plan['id']
        ]
    ];

    // Call Razorpay API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/plans');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($planData));
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $result = curl_exec($ch);
    $response = json_decode($result, true);

    if (isset($response['id'])) {
        echo "  -> Created: " . $response['id'] . "\n";
        $mapping[$plan['id']] = $response['id'];
    } else {
        echo "  -> Error: " . ($response['error']['description'] ?? 'Unknown') . "\n";
    }
    curl_close($ch);

    // Avoid rate limits
    usleep(500000);
}

echo "\n\n------------------------------------------------\n";
echo "COPY THIS ARRAY INTO YOUR config/config.php:\n";
echo "------------------------------------------------\n\n";

echo '$RAZORPAY_PLANS = [' . "\n";
foreach ($mapping as $localId => $rzpId) {
    echo "    '$localId' => '$rzpId',\n";
}
echo "    'default' => 'plan_S8Wasx7b5ThF2A' // Fallback\n";
echo "];\n";

echo "</pre>";
