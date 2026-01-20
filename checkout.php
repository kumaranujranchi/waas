<?php
/**
 * Checkout Page - Simplified
 */

$pageTitle = 'Checkout | SiteOnSub';
include __DIR__ . '/includes/header.php';

// Require login
requireLogin();

require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Subscription.php';

$productModel = new Product();

// Get plan ID from URL
$planId = $_GET['plan_id'] ?? null;

if (!$planId) {
    setFlashMessage('error', 'Please select a plan');
    redirect(baseUrl('index.php'));
}

// Get plan details
$plan = $productModel->getPricingPlanById($planId);

if (!$plan) {
    setFlashMessage('error', 'Invalid plan selected');
    redirect(baseUrl('index.php'));
}

// Calculate totals
$subtotal = $plan['price'];
$tax = calculateTax($subtotal);
$total = $subtotal + $tax;

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderModel = new Order();
    $subscriptionModel = new Subscription();

    try {
        // Create order
        $orderData = [
            'user_id' => getCurrentUserId(),
            'order_number' => generateOrderNumber(),
            'total_amount' => $subtotal,
            'tax_amount' => $tax,
            'discount_amount' => 0,
            'final_amount' => $total,
            'currency' => CURRENCY,
            'payment_status' => 'pending',
            'payment_method' => 'stripe', // Default to Stripe
            'billing_email' => getCurrentUser()['email'],
            'billing_name' => getCurrentUser()['full_name']
        ];

        $orderItems = [
            [
                'product_id' => $plan['product_id'],
                'plan_id' => $planId,
                'product_name' => $plan['product_name'],
                'plan_name' => $plan['plan_name'],
                'price' => $plan['price'],
                'quantity' => 1
            ]
        ];

        $orderResult = $orderModel->createOrder($orderData, $orderItems);

        if ($orderResult['success']) {
            // Create subscription
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d', strtotime("+{$plan['billing_cycle']} months"));

            $subscriptionData = [
                'user_id' => getCurrentUserId(),
                'product_id' => $plan['product_id'],
                'plan_id' => $planId,
                'subscription_status' => 'active',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'auto_renew' => true,
                'payment_method' => 'stripe'
            ];

            $subscriptionModel->createSubscription($subscriptionData);

            // Update order status to completed (in real scenario, this would be done after payment)
            $orderModel->updateOrderStatus($orderResult['order_id'], 'completed');

            setFlashMessage('success', 'Order placed successfully! Your subscription is now active.');
            redirect(baseUrl('dashboard/index.php'));
        }
    } catch (Exception $e) {
        setFlashMessage('error', 'Failed to process order. Please try again.');
    }
}
?>

<main class="flex-1 flex justify-center py-12 px-4">
    <div class="max-w-[720px] w-full space-y-8">
        <div class="text-center md:text-left">
            <h1 class="text-[#0f0e1b] dark:text-white text-4xl font-black leading-tight tracking-tight">Review and Pay
            </h1>
            <p class="text-primary dark:text-primary/80 text-lg font-medium mt-2">Complete your subscription to get
                started</p>
        </div>

        <div
            class="bg-white dark:bg-[#1e1d2d] rounded-xl shadow-xl overflow-hidden border border-gray-100 dark:border-white/5">
            <!-- Selected Plan -->
            <div class="p-6 border-b border-gray-100 dark:border-white/5 bg-primary/5">
                <div class="flex flex-col gap-4">
                    <span class="text-primary text-xs font-bold uppercase tracking-wider">Current Selection</span>
                    <h3 class="text-[#0f0e1b] dark:text-white text-xl font-bold">
                        <?php echo e($plan['product_name']); ?> -
                        <?php echo e($plan['plan_name']); ?>
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        <?php echo e($plan['plan_type']); ?> billing -
                        <?php echo formatPrice($plan['price']); ?> /
                        <?php echo $plan['billing_cycle']; ?> months
                    </p>
                </div>
            </div>

            <!-- Pricing Breakdown -->
            <div class="p-6 md:p-10">
                <div class="grid md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="p-4 bg-background-light dark:bg-background-dark rounded-lg flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary">verified_user</span>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Secure Transaction</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Your payment is encrypted
                                    using 256-bit SSL technology.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-background-light dark:bg-background-dark/50 p-6 rounded-xl flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    <?php echo formatPrice($subtotal); ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Tax (
                                    <?php echo (TAX_RATE * 100); ?>%)
                                </span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    <?php echo formatPrice($tax); ?>
                                </span>
                            </div>
                            <div class="h-px bg-gray-200 dark:bg-white/10 my-2"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                                <span class="text-2xl font-black text-primary">
                                    <?php echo formatPrice($total); ?>
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="" class="mt-8 space-y-4">
                            <button type="submit"
                                class="w-full py-4 bg-accent-green text-white rounded-lg font-bold text-lg shadow-lg hover:shadow-accent-green/20 hover:brightness-105 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">lock</span>
                                Complete Purchase
                            </button>
                            <a href="<?php echo baseUrl('product-detail.php?slug=' . $plan['product_id']); ?>"
                                class="w-full py-2 text-gray-500 dark:text-gray-400 text-sm font-medium hover:text-gray-700 dark:hover:text-white transition-colors text-center block">
                                Cancel and return to Product
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center items-center gap-8 text-sm text-gray-400 pb-10">
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">help</span> Support Help Center
            </span>
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">shield</span> Data Privacy Policy
            </span>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>