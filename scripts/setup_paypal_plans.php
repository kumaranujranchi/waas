<?php
/**
 * PayPal Plan Setup Automation Script
 * This script creates a PayPal Product and then creates recurring plans for all active services.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../includes/functions.php';

echo "--- PayPal Plan Automation starting ---\n";

// 1. Create a general product for the platform if not already done
echo "Creating/Getting PayPal Product...\n";
$productResponse = createPayPalProduct("SiteOnSub Services", "Subscription services for SiteOnSub platform");

if (isset($productResponse['error'])) {
    die("Error creating product: " . print_r($productResponse['error'], true) . "\n");
}

$paypalProductId = $productResponse['id'] ?? null;
if (!$paypalProductId) {
    // Check if it already exists or if response was different
    if (isset($productResponse['name']) && $productResponse['name'] === 'SiteOnSub Services') {
        // It might be an existing product response or similar
        $paypalProductId = $productResponse['id'];
    } else {
        die("Could not retrieve PayPal Product ID. Response: " . json_encode($productResponse) . "\n");
    }
}

echo "PayPal Product ID: $paypalProductId\n\n";

// 2. Fetch all active pricing plans from database
$db = Database::getInstance();
$plans = $db->fetchAll("SELECT pp.*, p.name as service_name FROM pricing_plans pp JOIN products p ON pp.product_id = p.id WHERE pp.status = 'active'");

if (empty($plans)) {
    echo "No active pricing plans found in database.\n";
    exit;
}

echo "Found " . count($plans) . " plans to process.\n";

foreach ($plans as $plan) {
    echo "Processing: " . $plan['service_name'] . " (" . $plan['plan_type'] . ") - Price: " . $plan['price'] . "\n";

    // Determine interval
    $intervalUnit = 'MONTH';
    $intervalCount = 1;

    if ($plan['plan_type'] === 'semi_annual' || $plan['plan_type'] === 'half_yearly') {
        $intervalCount = 6;
    } elseif ($plan['plan_type'] === 'yearly') {
        $intervalCount = 12;
    }

    $planName = $plan['service_name'] . " - " . ucfirst(str_replace('_', ' ', $plan['plan_type']));

    // Create Plan on PayPal
    $paypalPlan = createPayPalPlan($paypalProductId, $planName, $plan['price'], $intervalCount, $intervalUnit);

    if (isset($paypalPlan['id'])) {
        $paypalPlanId = $paypalPlan['id'];
        echo "Successfully created PayPal Plan: $paypalPlanId\n";

        // Update database
        $updated = $db->update('pricing_plans', ['paypal_plan_id' => $paypalPlanId], 'id = ?', [$plan['id']]);
        if ($updated) {
            echo "Database updated for Plan ID: " . $plan['id'] . "\n";
        } else {
            echo "Failed to update database for Plan ID: " . $plan['id'] . "\n";
        }
    } else {
        echo "Error creating PayPal Plan for " . $plan['service_name'] . ": " . json_encode($paypalPlan) . "\n";
    }
    echo "-------------------\n";
}

echo "\n--- Automation Complete ---\n";
echo "Note: All approximate conversions are done in USD as per global standard. Check config/config.php if you need to change currency.\n";
