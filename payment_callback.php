<?php
/**
 * Payment Callback - Handle Razorpay Response
 */

// Output buffering to prevent header errors from warnings
ob_start();

// Debugging - Remove in production later (optional, set to 0 to be safe)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Subscription.php';
require_once __DIR__ . '/classes/Mail.php';

// Defensive: Ensure SITE_URL is defined if config failed to load completely
if (!defined('SITE_URL')) {
    define('SITE_URL', 'https://siteonsub.com');
}

// Check if we have the necessary POST parameters
if (empty($_POST['razorpay_payment_id']) || empty($_POST['razorpay_subscription_id']) || empty($_POST['razorpay_signature'])) {
    setFlashMessage('error', 'Invalid payment response.');
    redirect(baseUrl('dashboard/index.php'));
}

$paymentId = $_POST['razorpay_payment_id'];
$subscriptionId = $_POST['razorpay_subscription_id'];
$signature = $_POST['razorpay_signature'];

// Verify Signature
$apiSecret = defined('RAZORPAY_KEY_SECRET') ? RAZORPAY_KEY_SECRET : '';
$expectedSignature = hash_hmac('sha256', $paymentId . '|' . $subscriptionId, $apiSecret);

if (hash_equals($expectedSignature, $signature)) {
    // Signature is valid
    try {
        $orderModel = new Order();
        $subscriptionModel = new Subscription();

        // 1. Find the pending order by transaction_id (which we stored as subscription_id temporarily)
        $order = $orderModel->getOrderByTransactionId($subscriptionId);

        if ($order) {
            // 2. Update Order Status
            $orderModel->updateOrderStatus($order['id'], 'completed');

            // Update transaction details
            // Ideally we should add a method to update specific fields, but for now we might assume updateOrderStatus handles basic status. 
            // If we need to save the actual payment_id, we might need a custom query or method.
            // keeping it simple: Status Completed is the priority.

            // 3. Create/Activate Subscription
            // We need to fetch plan details to know the duration
            // But since we are in a callback, we might depend on the order items to know which plan it was.

            $orderItems = $orderModel->getOrderItems($order['id']);
            $planId = $orderItems[0]['plan_id'] ?? null;
            $productId = $orderItems[0]['product_id'] ?? null;

            if ($planId) {
                // Calculate dates based on Razorpay subscription or defaults
                // Since it's a subscription, Razorpay handles the schedule, but we keep a local record.
                $startDate = date('Y-m-d');
                $billingCycleMonths = ($orderItems[0]['plan_name'] == 'Yearly') ? 12 : (($orderItems[0]['plan_name'] == '6 Months') ? 6 : 1);
                // A better way would be to fetch the plan from DB, but for speed relying on logic or stored name if consistent.
                // Let's refetch plan to be safe if possible, or defaulting to 1 month if unknown.

                // Fetch Plan to get accurate billing cycle
                require_once __DIR__ . '/models/Product.php';
                $productModel = new Product();
                $plan = $productModel->getPricingPlanById($planId);

                $months = $plan['billing_cycle'] ?? 1;
                $endDate = date('Y-m-d', strtotime("+$months months"));

                $subscriptionData = [
                    'user_id' => $order['user_id'],
                    'product_id' => $productId,
                    'plan_id' => $planId,
                    'subscription_status' => 'active',
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'auto_renew' => true,
                    'payment_method' => 'razorpay',
                    'stripe_subscription_id' => $subscriptionId // Storing Razorpay Sub ID in this column for now
                ];

                $subId = $subscriptionModel->createSubscription($subscriptionData);
                if ($subId) {
                    $orderModel->setSubscriptionId($order['id'], $subId);

                    // Send Order Confirmation Email
                    Mail::sendOrderConfirmation($order['user_email'], $order['user_name'], $order);
                }
            }

            setFlashMessage('success', 'Payment successful! Your subscription is now active.');
            redirect(baseUrl('payment_success.php?order_id=' . $order['id']));

        } else {
            // Order not found for this subscription ID
            setFlashMessage('error', 'Order not found for this payment.');
            redirect(baseUrl('dashboard/index.php'));
        }

    } catch (Exception $e) {
        // Log error
        error_log("Payment Callback Error: " . $e->getMessage());
        setFlashMessage('error', 'Payment processed but failed to update local records. Please contact support.');
        redirect(baseUrl('dashboard/index.php'));
    }

} else {
    // Signature verification failed
    setFlashMessage('error', 'Payment verification failed! Security signature mismatch.');
    redirect(baseUrl('dashboard/index.php'));
}
