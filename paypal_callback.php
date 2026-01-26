<?php
/**
 * PayPal Callback Handler
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Subscription.php';

$status = $_GET['status'] ?? null;
$subscriptionId = $_GET['subscription_id'] ?? null;

if ($status === 'cancel') {
    setFlashMessage('error', 'Payment was cancelled.');
    redirect(baseUrl('index.php'));
}

if (!$subscriptionId) {
    setFlashMessage('error', 'Invalid request from PayPal.');
    redirect(baseUrl('index.php'));
}

// 1. Verify Subscription Status with PayPal
$paypalSub = verifyPayPalSubscription($subscriptionId);

if (isset($paypalSub['error']) || !isset($paypalSub['status'])) {
    setFlashMessage('error', 'Failed to verify payment with PayPal.');
    redirect(baseUrl('index.php'));
}

// Check if status is ACTIVE or APPROVED
if ($paypalSub['status'] === 'ACTIVE' || $paypalSub['status'] === 'APPROVED') {
    $orderModel = new Order();
    $subscriptionModel = new Subscription();

    // 2. Find Pending Order
    $order = $orderModel->getOrderByTransactionId($subscriptionId);

    if (!$order) {
        // Fallback or Log Error
        setFlashMessage('error', 'Payment successful, but order not found. Please contact support.');
        redirect(baseUrl('index.php'));
    }

    if ($order['payment_status'] === 'completed') {
        redirect(baseUrl('order-success.php?id=' . $order['id']));
    }

    // 3. Update Order and Create Subscription
    $orderItems = $orderModel->getOrderItems($order['id']);
    $item = $orderItems[0]; // Assuming single item for now

    $startDate = date('Y-m-d H:i:s');
    // Calculate end date based on plan billing cycle
    $billingCycle = 1; // Default
    // Get plan details for better accuracy
    require_once __DIR__ . '/models/Product.php';
    $productModel = new Product();
    $plan = $productModel->getPricingPlanById($item['plan_id']);
    if ($plan) {
        $billingCycle = $plan['billing_cycle'];
    }

    $endDate = date('Y-m-d H:i:s', strtotime("+{$billingCycle} months"));

    $subData = [
        'user_id' => $order['user_id'],
        'product_id' => $item['product_id'],
        'plan_id' => $item['plan_id'],
        'stripe_subscription_id' => $subscriptionId, // Using this column for PayPal Sub ID as well
        'subscription_status' => 'active',
        'start_date' => $startDate,
        'end_date' => $endDate
    ];

    $newSubId = $subscriptionModel->createSubscription($subData);

    if ($newSubId) {
        // Link order to subscription and complete it
        $orderModel->setSubscriptionId($order['id'], $newSubId);
        $orderModel->updatePaymentDetails($order['id'], $subscriptionId, 'completed');

        setFlashMessage('success', 'Thank you! Your subscription is now active.');
        redirect(baseUrl('order-success.php?id=' . $order['id']));
    } else {
        setFlashMessage('error', 'Payment verified, but failed to activate services. Please contact support.');
        redirect(baseUrl('index.php'));
    }
} else {
    setFlashMessage('error', 'Payment status is: ' . $paypalSub['status']);
    redirect(baseUrl('index.php'));
}
