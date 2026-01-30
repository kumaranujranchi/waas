<?php
/**
 * Checkout Page
 */

// Start session and includes BEFORE any output
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php'; // Fix: Include database config
require_once __DIR__ . '/includes/functions.php';

// Models
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Subscription.php';

$productModel = new Product();
$currentUser = getCurrentUser(); // Will be null if not logged in
$isLoggedIn = isLoggedIn();

// Get plan ID or Order ID from URL
$planId = $_GET['plan_id'] ?? null;
$orderId = $_GET['order_id'] ?? null;

if (!$planId && !$orderId) {
    setFlashMessage('error', 'Invalid request');
    header("Location: " . baseUrl('index.php'));
    exit;
}

// Logic for Resuming Payment (Order ID)
$existingOrder = null;
if ($orderId) {
    $orderModel = new Order();
    $existingOrder = $orderModel->getOrderById($orderId);

    if (!$existingOrder || $existingOrder['user_id'] != getCurrentUserId() || $existingOrder['payment_status'] === 'completed') {
        setFlashMessage('error', 'Invalid or completed order');
        redirect(baseUrl('dashboard/orders.php'));
    }

    // Get the plan from the order items
    // Assuming single item order for now as per current logic
    $orderItems = $orderModel->getOrderItems($orderId);
    if (!empty($orderItems)) {
        $planId = $orderItems[0]['plan_id'];
    }
}

// Get plan details
$plan = $productModel->getPricingPlanById($planId);

if (!$plan) {
    setFlashMessage('error', 'Invalid plan selected');
    redirect(baseUrl('index.php'));
}

// Calculate totals (Use existing order totals if available to match exactly)
if ($existingOrder) {
    $subtotal = $existingOrder['total_amount'];
    $tax = $existingOrder['tax_amount'];
    $total = $existingOrder['final_amount'];
} else {
    $subtotal = $plan['price'];
    $tax = calculateTax($subtotal);
    $total = $subtotal + $tax;
}

// Initialize error
$error = null;
$subscriptionId = null; // Initialize variable

// Handle Checkout Initiation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$isLoggedIn) {
        $redirectUrl = $orderId ? 'checkout.php?order_id=' . $orderId : 'checkout.php?plan_id=' . $planId;
        setFlashMessage('error', 'Please login to complete your purchase.');
        redirect(baseUrl('auth/login.php?redirect=' . urlencode($redirectUrl)));
    }


    $orderModel = new Order();
    $subscriptionModel = new Subscription();

    // Branch based on method
    // Default to Razorpay
    $paymentMethod = 'razorpay';

    // RAZORPAY FLOW
    // 1. Get Razorpay Plan ID from Config/Mapping
    global $RAZORPAY_PLANS;

    // Debug: Ensure array is available or reload config if needed
    if (!isset($RAZORPAY_PLANS)) {
        require __DIR__ . '/config/config.php';
    }

    // Check if configuration exists
    $razorpayPlanId = null;

    // 1. Check Database First (Best Practice)
    if (!empty($plan['razorpay_plan_id'])) {
        $razorpayPlanId = $plan['razorpay_plan_id'];
    }
    // 2. Fallback to Config array (Legacy)
    elseif (isset($RAZORPAY_PLANS) && is_array($RAZORPAY_PLANS)) {
        // Try exact match -> try config default -> hardcoded fallback
        $razorpayPlanId = $RAZORPAY_PLANS[$planId] ?? $RAZORPAY_PLANS['default'] ?? 'plan_S8Wasx7b5ThF2A';
    }

    if (!$razorpayPlanId) {
        $debugInfo = isset($RAZORPAY_PLANS) ? "Keys available: " . implode(', ', array_keys($RAZORPAY_PLANS)) : "Config not loaded";
        $error = "Payment plan configuration missing for Plan ID: {$planId}. ({$debugInfo}) Please contact support.";
    } else {
        // 2. Create Subscription on Razorpay
        $razorpaySub = createRazorpaySubscription($razorpayPlanId);

        if (isset($razorpaySub['error'])) {
            $error = "Gateway Error: " . ($razorpaySub['error']['description'] ?? $razorpaySub['error']);
        } elseif (isset($razorpaySub['id'])) {
            // Success - Get Subscription ID
            $subscriptionId = $razorpaySub['id'];

            // 3. Create or Update Local Order
            if ($existingOrder) {
                // Update existing order with new payment method/ID if needed
                // But mainly just keep the ID
                $localOrderId = $existingOrder['id'];

                // Update the payment_id in DB to the new subscription ID
                $orderModel->updateOrder($localOrderId, ['payment_id' => $subscriptionId]);
            } else {
                // Create New Order
                $orderData = [
                    'user_id' => getCurrentUserId(),
                    'order_number' => generateOrderNumber(),
                    'total_amount' => $subtotal,
                    'tax_amount' => $tax,
                    'discount_amount' => 0,
                    'final_amount' => $total,
                    'currency' => CURRENCY,
                    'payment_status' => 'pending',
                    'payment_method' => 'razorpay_subscription',
                    'billing_email' => $currentUser['email'],
                    'billing_name' => $currentUser['full_name'],
                    'payment_id' => $subscriptionId // Store RZP Sub ID temporarily
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

                $localOrderId = $orderModel->createOrder($orderData, $orderItems);

                // Trigger Payment Pending Email
                sendPaymentPendingEmail($localOrderId);
            }

            // 4. Trigger Razorpay JS (handled below in HTML)
        } else {
            $error = "Unknown error from payment gateway.";
        }
    }
}

// AFTER ALL LOGIC, set page title and include header
$pageTitle = 'Checkout | SiteOnSub';
include __DIR__ . '/includes/header.php';
?>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<main class="min-h-[70vh] flex justify-center py-12 px-4 relative z-10">
    <div class="max-w-[720px] w-full space-y-8">
        <div class="text-center md:text-left">
            <h1 class="text-[#0f0e1b] dark:text-white text-4xl font-extrabold leading-tight tracking-tight">Review and
                Pay</h1>
            <p class="text-blue-600 dark:text-blue-400 text-lg font-medium mt-2">Complete your subscription to get
                started</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <div
            class="bg-white dark:bg-[#1e1d2d] rounded-xl shadow-xl overflow-hidden border border-gray-100 dark:border-white/5">
            <!-- Selected Plan -->
            <div class="p-6 border-b border-gray-100 dark:border-white/5 bg-primary/5">
                <div class="flex flex-col gap-2">
                    <span class="text-blue-600 text-xs font-bold uppercase tracking-wider">Current Selection</span>
                    <h3 class="text-[#0f0e1b] dark:text-white text-xl font-bold">
                        <?php echo e($plan['product_name']); ?> - <?php echo e($plan['plan_name']); ?>
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        <?php echo e(ucfirst($plan['plan_type'])); ?> billing -
                        <?php echo formatPrice($plan['price']); ?> / <?php echo $plan['billing_cycle']; ?> months
                    </p>
                </div>
            </div>

            <!-- Pricing Breakdown -->
            <div class="p-6 md:p-10">
                <!-- Payment Method Selection -->
                <form method="POST" action="" id="checkout-form" class="space-y-8">
                    <!-- Default Payment Method: Razorpay -->
                    <input type="hidden" name="payment_method" value="razorpay">

                    <div class="grid md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-lg flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary">verified_user</span>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Secure Transaction</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Your payment is encrypted
                                        using 256-bit SSL technology.</p>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-lg flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary">sync</span>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Automatic Renewal</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Cancel anytime from your
                                        dashboard.</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-white/5 p-6 rounded-xl flex flex-col justify-between">
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
                                    <span class="text-2xl font-bold text-blue-600">
                                        <?php echo formatPrice($total); ?>
                                    </span>
                                </div>
                            </div>
                            <?php if ($isLoggedIn): ?>
                                <div class="mt-8 space-y-4">
                                    <button type="submit"
                                        class="w-full py-4 bg-primary hover:bg-primary-dark text-white rounded-lg font-bold text-lg shadow-lg transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined">payments</span>
                                        Checkout Now
                                    </button>
                                    <a href="<?php echo baseUrl('product-detail.php?slug=' . ($plan['product_slug'] ?? '')); ?>"
                                        class="w-full py-2 text-gray-500 dark:text-gray-400 text-sm font-medium hover:text-gray-700 dark:hover:text-white transition-colors text-center block">
                                        Cancel
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="mt-8 space-y-4">
                                    <a href="<?php echo baseUrl('auth/login.php?redirect=' . urlencode('checkout.php?plan_id=' . $planId)); ?>"
                                        class="w-full py-4 bg-primary hover:bg-primary-dark text-white rounded-lg font-bold text-lg shadow-lg hover:shadow-primary/20 brightness-110 transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined">login</span>
                                        Login to Continue
                                    </a>
                                    <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                                        You need an account to manage your subscription.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-wrap justify-center items-center gap-6 text-sm text-gray-400 pb-10">
            <div class="flex items-center gap-2 grayscale opacity-70">
                <img src="<?php echo baseUrl('assets/banklogo/Visa_Inc._logo.svg'); ?>" class="h-4">
                <img src="<?php echo baseUrl('assets/banklogo/MasterCard_early_1990s_logo.svg'); ?>" class="h-4">
            </div>
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">lock</span> Trusted by Razorpay
            </span>
        </div>
    </div>
</main>

<?php if ($subscriptionId): ?>
    <script>     var options = { "key": "<?php echo RAZORPAY_KEY_ID; ?>", "subscription_id": "<?php echo $subscriptionId; ?>", "name": "SiteOnSub", "description": "<?php echo $plan['product_name'] . ' - ' . $plan['plan_name']; ?>", "image": "<?php echo baseUrl('assets/images/logo.png'); ?>", "callback_url": "<?php echo baseUrl('payment_callback.php'); ?>", "prefill": { "name": "<?php echo e($currentUser['full_name']); ?>", "email": "<?php echo e($currentUser['email']); ?>", "contact": "<?php echo e($currentUser['phone'] ?? ''); ?>" }, "theme": { "color": "#5048e5" } }; var rzp1 = new Razorpay(options); rzp1.open();
    </script>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
<!-- Execution Complete Marker -->