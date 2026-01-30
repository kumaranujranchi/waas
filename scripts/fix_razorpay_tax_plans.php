<?php
/**
 * Razorpay Tax Fix Script
 * -----------------------------------
 * This script will:
 * 1. Fetch all active pricing plans.
 * 2. Calculate the Total Amount = Price + (Price * Tax Rate).
 * 3. Create NEW plans on Razorpay with this correct amount.
 * 4. Update the local database.
 *
 * Usage: Browser -> https://siteonsub.com/scripts/fix_razorpay_tax_plans.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';

// HTML styling
echo '<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Tax Fix</title>
    <style>
        body { font-family: monospace; background: #1a1a1a; color: #00ff00; padding: 20px; line-height: 1.5; }
        .error { color: #ff5555; }
        .success { color: #55ff55; }
        .info { color: #55ffff; }
        hr { border: 0; border-top: 1px solid #333; margin: 10px 0; }
    </style>
</head>
<body>
<h3>fixing Razorpay Plans (Adding Tax)...</h3>';

echo "Using Key ID: " . substr('rzp_live_RwX28Qv2VotZir', 0, 15) . "...<br><br>";

// Hardcoded keys to ensure standalone execution
$LIVE_KEY_ID = 'rzp_live_RwX28Qv2VotZir';
$LIVE_KEY_SECRET = '0OEb6iiMFr84RUcA90wXZVYY';

function createRazorpayPlan($name, $amount, $currency = 'INR', $period = 'monthly', $interval = 1, $keyId, $keySecret)
{
    $url = 'https://api.razorpay.com/v1/plans';

    $data = [
        'period' => $period,
        'interval' => $interval,
        'item' => [
            'name' => $name,
            'amount' => round($amount * 100), // Amount in paise, rounded to nearest integer
            'currency' => $currency,
            'description' => "Subscription for " . $name . " (Incl. Tax)"
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
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

    // Use tax rate from config or default to 18%
    $taxRate = defined('TAX_RATE') ? TAX_RATE : 0.18;
    echo "Using Tax Rate: " . ($taxRate * 100) . "%<br><br>";

    // Fetch all pricing plans
    $sql = "SELECT pp.*, p.name as product_name 
            FROM pricing_plans pp 
            JOIN products p ON pp.product_id = p.id 
            WHERE pp.status = 'active'";

    $plans = $db->fetchAll($sql);

    echo "Found " . count($plans) . " active plans to update.<br><br>";

    foreach ($plans as $plan) {
        $basePrice = floatval($plan['price']);
        $taxAmount = $basePrice * $taxRate;
        $totalAmount = $basePrice + $taxAmount;

        echo "<div class='info'>Processing: " . $plan['product_name'] . " - " . $plan['plan_name'] . "</div>";
        echo "Base: {$basePrice} + Tax: {$taxAmount} = <strong>Total: {$totalAmount}</strong><br>";

        // Determine period and interval
        $period = 'monthly';
        $interval = 1;

        if ($plan['plan_type'] === 'semi_annual') {
            $period = 'monthly';
            $interval = 6;
        } elseif ($plan['plan_type'] === 'yearly') {
            $period = 'yearly';
            $interval = 1;
        }

        $planName = $plan['product_name'] . ' - ' . $plan['plan_name'];

        // Create NEW Plan on Razorpay
        $rzpPlan = createRazorpayPlan($planName, $totalAmount, 'INR', $period, $interval, $LIVE_KEY_ID, $LIVE_KEY_SECRET);

        if (isset($rzpPlan['id'])) {
            echo "<span class='success'>  -> Created Corrected Plan: " . $rzpPlan['id'] . "</span><br>";

            // Update Database with NEW ID
            $updateSql = "UPDATE pricing_plans SET razorpay_plan_id = ? WHERE id = ?";
            $db->query($updateSql, [$rzpPlan['id'], $plan['id']]);
            echo "<span class='success'>  -> Database updated.</span><br>";
        } else {
            echo "<span class='error'>  -> ERROR: " . ($rzpPlan['error'] ?? 'Unknown error') . "</span><br>";
        }

        echo "<hr>";
        flush();
    }

    echo "<h3>Fix Completed! Corrected plans are now live.</h3>";
    echo "<p>Please delete this file from your server: <code>scripts/fix_razorpay_tax_plans.php</code></p>";
    echo "</body></html>";

} catch (Exception $e) {
    echo "<h3 class='error'>Critical Error: " . $e->getMessage() . "</h3></body></html>";
}
