<?php
/**
 * User Orders Page
 */

$pageTitle = 'My Orders';
include __DIR__ . '/includes/header.php';

require_once __DIR__ . '/../models/Order.php';

$orderModel = new Order();
$userId = getCurrentUserId();
$orders = $orderModel->getUserOrders($userId);
?>

<!-- Orders Header -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-2">My Orders</h2>
    <p class="text-gray-600 dark:text-gray-400">View and manage all your orders</p>
</div>

<?php if (empty($orders)): ?>
    <div class="bg-white dark:bg-white/5 rounded-lg p-12 text-center border border-gray-200 dark:border-white/10">
        <span class="material-symbols-outlined text-6xl text-gray-300 mb-4 inline-block">shopping_bag</span>
        <p class="text-gray-600 dark:text-gray-400 mb-6">No orders yet</p>
        <a href="<?php echo baseUrl('index.php'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary-dark transition-all">
            <span class="material-symbols-outlined">shopping_cart</span>
            Start Shopping
        </a>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($orders as $order): ?>
            <div class="bg-white dark:bg-white/5 rounded-lg border border-gray-200 dark:border-white/10 hover:shadow-lg transition-all">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-1">
                                Order <?php echo e($order['order_number']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Placed on <?php echo formatDate($order['created_at']); ?>
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-sm font-bold <?php 
                                $statusClass = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                ];
                                echo $statusClass[$order['payment_status']] ?? $statusClass['pending'];
                            ?>">
                                <?php echo ucfirst($order['payment_status']); ?>
                            </span>
                            <span class="text-2xl font-bold text-primary">
                                <?php echo formatPrice($order['final_amount']); ?>
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-white/10 pt-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 uppercase font-bold mb-1">Subtotal</p>
                                <p class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                    <?php echo formatPrice($order['subtotal']); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 uppercase font-bold mb-1">Tax</p>
                                <p class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                    <?php echo formatPrice($order['tax_amount']); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 uppercase font-bold mb-1">Discount</p>
                                <p class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                    <?php echo formatPrice($order['discount_amount'] ?? 0); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 uppercase font-bold mb-1">Items</p>
                                <p class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                    <?php echo $order['quantity'] ?? 1; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-white/10 mt-4 pt-4">
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="px-4 py-2 bg-primary/10 text-primary rounded-lg font-medium hover:bg-primary/20 transition-all">
                                View Details
                            </a>
                            <?php if ($order['payment_status'] === 'completed'): ?>
                                <a href="#" class="px-4 py-2 border border-gray-300 dark:border-white/20 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                                    Download Invoice
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
