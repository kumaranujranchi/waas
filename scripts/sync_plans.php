<?php
/**
 * Sync Local Plans to Razorpay
 * 
 * This script fetches all plans from the local database,
 * creates them in Razorpay, and UPDATES the local database
 * with the new Razorpay Plan ID.
 */

// Debug on
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';

// Razorpay Keys
$keyId = defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : 'rzp_test_S8VnZcp2OFweOn';
$keySecret = defined('RAZORPAY_KEY_SECRET') ? RAZORPAY_KEY_SECRET : 'N1Df1w3L2vwYv1HHTiGwG0bA';

echo "<h3>Syncing Plans with Razorpay...</h3>";
echo "<pre>";

// 1. Fetch Plans from DB
$db = Database::getInstance();
$plans = $db->fetchAll("SELECT p.*, prod.name as product_name FROM pricing_plans p JOIN products prod ON p.product_id = prod.id");

foreach ($plans as $plan) {
    echo "Processing: " . $plan['product_name'] . " - " . $plan['plan_name'] . "...\n";

    // Convert price to paise (Razorpay expects integer)
    $amount = (int) ($plan['price'] * 100);
    $currency = 'INR';
    $primaryPeriod = $plan['billing_cycle']; // Assuming this is integer months, e.g. 1, 6, 12

    // Determine Period and Interval
    // Razorpay supports 'daily', 'weekly', 'monthly', 'yearly'
    // Interval can be 1-xx

    $period = 'monthly';
    $interval = $plan['billing_cycle']; // Default to X months

    // Optimize for standard periods
    if ($interval == 12) {
        $period = 'yearly';
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

        // Update Database
        try {
            $updateSql = "UPDATE pricing_plans SET razorpay_plan_id = ? WHERE id = ?";
            // We use direct query if possible, or try catch
            $db->query($updateSql, [$response['id'], $plan['id']]);
            echo "  -> Saved to Database (pricing_plans.razorpay_plan_id).\n";
        } catch (Exception $e) {
            echo "  -> DB Error: " . $e->getMessage() . " (Did you run the migration?)\n";
        }

    } else {
        echo "  -> Error: " . ($response['error']['description'] ?? 'Unknown') . "\n";
    }
    curl_close($ch);

    // Avoid rate limits
    usleep(500000);
}

echo "\n\n------------------------------------------------\n";
echo "SYNC COMPLETE.\n";
echo "All plans have been created and saved to your database.\n";
echo "------------------------------------------------\n";
echo "</pre>";
