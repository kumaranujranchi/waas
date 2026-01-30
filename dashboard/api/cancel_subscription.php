<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../models/Subscription.php';

// Ensure user is logged in
if (!isLoggedIn()) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$subscriptionId = $input['subscription_id'] ?? null;

if (!$subscriptionId) {
    echo json_encode(['success' => false, 'message' => 'Subscription ID is required']);
    exit();
}

try {
    $subscriptionModel = new Subscription();
    $userId = getCurrentUserId();

    // Fetch subscription
    $subscription = $subscriptionModel->getSubscriptionById($subscriptionId);

    if (!$subscription) {
        echo json_encode(['success' => false, 'message' => 'Subscription not found']);
        exit();
    }

    // Verify ownership
    if ($subscription['user_id'] != $userId) {
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        exit();
    }

    // Call Razorpay API to cancel
    if (!empty($subscription['razorpay_subscription_id'])) {
        $result = cancelRazorpaySubscription($subscription['razorpay_subscription_id']);

        if (isset($result['error'])) {
            // Even if Razorpay fails, if it says "already cancelled", we should update local DB
            // But for now, let's report error unless it's strictly "bad_request" for already cancelled
            if (strpos(strtolower($result['error']['description'] ?? ''), 'cancelled') === false) {
                echo json_encode(['success' => false, 'message' => 'Razorpay Error: ' . ($result['error']['description'] ?? 'Unknown')]);
                exit();
            }
        }
    }

    // Update Local DB
    $subscriptionModel->cancelSubscription($subscriptionId);

    echo json_encode(['success' => true, 'message' => 'Subscription cancelled successfully']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
}
