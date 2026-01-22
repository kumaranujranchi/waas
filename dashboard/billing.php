<?php
/**
 * User Billing Page
 */

$pageTitle = 'Billing & Payments';
include __DIR__ . '/includes/header.php';

require_once __DIR__ . '/../models/Order.php';

$orderModel = new Order();
$userId = getCurrentUserId();
$orders = $orderModel->getUserOrders($userId);

// Calculate totals
$totalSpent = array_sum(array_column($orders, 'final_amount'));
$completedOrders = array_filter($orders, fn($o) => $o['payment_status'] === 'completed');
?>

<!-- Billing Header -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-2">Billing & Payments</h2>
    <p class="text-gray-600 dark:text-gray-400">Manage your billing information and view payment history</p>
</div>

<!-- Billing Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Spent</p>
                <p class="text-3xl font-bold text-[#0f0e1b] dark:text-white">
                    <?php echo formatPrice($totalSpent); ?>
                </p>
            </div>
            <span class="material-symbols-outlined text-5xl text-primary/20">payments</span>
        </div>
    </div>

    <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Completed Orders</p>
                <p class="text-3xl font-bold text-accent-green">
                    <?php echo count($completedOrders); ?>
                </p>
            </div>
            <span class="material-symbols-outlined text-5xl text-accent-green/20">check_circle</span>
        </div>
    </div>

    <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Average Order</p>
                <p class="text-3xl font-bold text-purple-600">
                    <?php echo !empty($orders) ? formatPrice($totalSpent / count($orders)) : '$0.00'; ?>
                </p>
            </div>
            <span class="material-symbols-outlined text-5xl text-purple-600/20">trending_up</span>
        </div>
    </div>
</div>

<!-- Payment Methods -->
<div class="bg-white dark:bg-white/5 rounded-lg border border-gray-200 dark:border-white/10 p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white">Payment Methods</h3>
        <button class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Add Payment Method
        </button>
    </div>

    <div class="space-y-4">
        <!-- Sample Payment Method -->
        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-white/10 rounded-lg">
            <div class="flex items-center gap-4">
                <div class="w-12 h-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded flex items-center justify-center text-white font-bold text-xs">
                    VISA
                </div>
                <div>
                    <p class="font-bold text-[#0f0e1b] dark:text-white">Visa ending in 4242</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Expires 12/2026</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/10 rounded transition-all">
                    Edit
                </button>
                <button class="px-3 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-all">
                    Remove
                </button>
            </div>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400">No payment methods added. Add one to make purchases easier.</p>
    </div>
</div>

<!-- Billing History -->
<div class="bg-white dark:bg-white/5 rounded-lg border border-gray-200 dark:border-white/10 p-6">
    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-6">Billing History</h3>

    <?php if (empty($orders)): ?>
        <div class="text-center py-8">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3 inline-block">receipt</span>
            <p class="text-gray-600 dark:text-gray-400">No billing history yet</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Invoice #</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                <?php echo formatDate($order['created_at']); ?>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-[#0f0e1b] dark:text-white">
                                <?php echo e($order['order_number']); ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                Order Payment
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo formatPrice($order['final_amount']); ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-bold rounded-full <?php 
                                    $statusClass = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                    echo $statusClass[$order['payment_status']] ?? $statusClass['pending'];
                                ?>">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <?php if ($order['payment_status'] === 'completed'): ?>
                                    <a href="#" class="text-primary font-medium hover:underline">Download</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
