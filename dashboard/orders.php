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
        <a href="<?php echo baseUrl('index.php'); ?>"
            class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary-dark transition-all">
            <span class="material-symbols-outlined">shopping_cart</span>
            Start Shopping
        </a>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($orders as $order): ?>
            <div
                class="bg-white dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span
                                    class="bg-primary/10 text-primary text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Order</span>
                                <h3 class="text-xl font-extrabold text-[#0f0e1b] dark:text-white">
                                    <?php echo e($order['order_number']); ?>
                                </h3>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">calendar_today</span>
                                Placed on <?php echo formatDate($order['created_at']); ?>
                            </p>
                        </div>
                        <div class="flex flex-col md:items-end gap-2">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold shadow-sm <?php
                            $payStatusClass = [
                                'pending' => 'bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-900/30',
                                'completed' => 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-900/30',
                                'failed' => 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30',
                            ];
                            echo $payStatusClass[$order['payment_status']] ?? $payStatusClass['pending'];
                            ?>">
                                Pay: <?php echo ucfirst($order['payment_status']); ?>
                            </span>
                            <?php if (!empty($order['order_status'])): ?>
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold shadow-sm mt-2 <?php
                                $opsClass = [
                                    'pending' => 'bg-gray-50 text-gray-700 border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-white/10',
                                    'processing' => 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-900/30',
                                    'completed' => 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-900/30',
                                    'cancelled' => 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30',
                                ];
                                echo $opsClass[$order['order_status']] ?? $opsClass['pending'];
                                ?>">
                                    Order: <?php echo ucfirst($order['order_status']); ?>
                                </span>
                            <?php endif; ?>
                            <span class="text-3xl font-black text-primary">
                                <?php echo formatPrice($order['final_amount']); ?>
                            </span>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-2 lg:grid-cols-4 gap-6 p-6 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5">
                        <div class="space-y-1">
                            <p class="text-[10px] text-gray-500 dark:text-gray-500 uppercase font-black tracking-widest">
                                Subtotal</p>
                            <p class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo formatPrice($order['total_amount']); ?>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] text-gray-500 dark:text-gray-500 uppercase font-black tracking-widest">Tax
                                (VAT)</p>
                            <p class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo formatPrice($order['tax_amount']); ?>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] text-gray-500 dark:text-gray-500 uppercase font-black tracking-widest">
                                Discount</p>
                            <p class="text-lg font-bold text-accent-green">
                                <?php echo formatPrice($order['discount_amount'] ?? 0); ?>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] text-gray-500 dark:text-gray-500 uppercase font-black tracking-widest">
                                Quantity</p>
                            <p class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo ($order['quantity'] ?? 1) . ' Items'; ?>
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex flex-wrap gap-3">
                            <a href="#"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-bold hover:bg-primary-dark shadow-md shadow-primary/20 transition-all">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                View Details
                            </a>
                            <?php if ($order['payment_status'] === 'completed'): ?>
                                <a href="<?php echo baseUrl('dashboard/invoice.php?order_id=' . $order['id']); ?>" target="_blank"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-200 rounded-lg font-bold hover:bg-gray-50 dark:hover:bg-white/20 transition-all">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    Invoice
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if ($order['payment_status'] === 'pending'): ?>
                            <a href="#" class="text-sm font-bold text-primary hover:underline">Complete Payment →</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>