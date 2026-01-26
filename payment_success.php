<?php
/**
 * Payment Success Page
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Order.php';

$pageTitle = 'Payment Successful | SiteOnSub';
include __DIR__ . '/includes/header.php';

$orderId = $_GET['order_id'] ?? null;
$order = null;

if ($orderId) {
    $orderModel = new Order();
    $order = $orderModel->getOrderById($orderId);
}

// Redirect to dashboard if no order found or order doesn't belong to user
if (!$order || $order['user_id'] != getCurrentUserId()) {
    redirect(baseUrl('dashboard/index.php'));
}
?>

<main class="flex-1 flex items-center justify-center py-12 px-4 bg-gray-50 dark:bg-[#0f0e1b]">
    <div
        class="max-w-[600px] w-full bg-white dark:bg-[#1e1d2d] rounded-2xl shadow-2xl p-8 md:p-12 text-center border border-gray-100 dark:border-white/5">

        <!-- Success Animation/Icon -->
        <div class="mb-8 relative inline-block">
            <div
                class="size-24 bg-accent-green/10 rounded-full flex items-center justify-center text-accent-green mx-auto animate-bounce">
                <span class="material-symbols-outlined text-6xl">check_circle</span>
            </div>
            <!-- Decorative elements -->
            <div class="absolute -top-2 -right-2 size-6 bg-primary rounded-full animate-ping opacity-20"></div>
            <div class="absolute -bottom-2 -left-2 size-4 bg-accent-green rounded-full animate-pulse opacity-30"></div>
        </div>

        <h1 class="text-3xl md:text-4xl font-black text-[#0f0e1b] dark:text-white mb-4">
            Payment Successful!
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-10 leading-relaxed font-medium">
            Thank you for your purchase. Your subscription is now active and your order is being processed.
        </p>

        <!-- Order Summary Card -->
        <div
            class="bg-gray-50 dark:bg-white/5 rounded-xl p-6 text-left space-y-4 mb-10 border border-gray-100 dark:border-white/5">
            <h3 class="text-sm font-black uppercase tracking-widest text-[#545095] dark:text-primary mb-2">Order Details
            </h3>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Order Number:</span>
                <span class="font-bold text-[#0f0e1b] dark:text-white">
                    <?php echo e($order['order_number']); ?>
                </span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Date:</span>
                <span class="font-bold text-[#0f0e1b] dark:text-white">
                    <?php echo formatDate($order['created_at']); ?>
                </span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Total Amount:</span>
                <span class="text-lg font-black text-primary">
                    <?php echo formatPrice($order['final_amount']); ?>
                </span>
            </div>
            <div class="flex justify-between items-center text-sm pt-4 border-t border-gray-200 dark:border-white/10">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Status:</span>
                <span
                    class="px-3 py-1 bg-accent-green/10 text-accent-green text-[10px] font-black uppercase tracking-wider rounded-full">Completed</span>
            </div>
        </div>

        <!-- Call to Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="<?php echo baseUrl('dashboard/index.php'); ?>"
                class="flex-1 py-4 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold transition-all shadow-lg hover:shadow-primary/30 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-xl">speed</span>
                Go to Dashboard
            </a>
            <a href="<?php echo baseUrl('dashboard/invoice.php?order_id=' . $order['id']); ?>" target="_blank"
                class="flex-1 py-4 bg-white dark:bg-white/5 text-[#0f0e1b] dark:text-white border-2 border-gray-200 dark:border-white/10 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-xl">receipt</span>
                View Invoice
            </a>
        </div>

        <p class="mt-8 text-xs text-gray-400 dark:text-gray-500 font-medium">
            A confirmation email has been sent to your registered email address.
        </p>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>