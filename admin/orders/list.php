<?php
/**
 * Admin - Orders List
 */

$pageTitle = 'Manage Orders';
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/User.php';

$orderModel = new Order();
$orders = $orderModel->getAllOrders();
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
        <div>
            <h1 class="text-4xl font-black text-[#0f0e1b] dark:text-white mb-2">Manage Orders</h1>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-[0.2em]">
                <?php echo count($orders); ?> Total Transactions
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div
                class="px-4 py-2 bg-white dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 rounded-xl flex items-center gap-2">
                <span class="size-2 rounded-full bg-accent-green animate-pulse"></span>
                <span class="text-xs font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white">Live
                    System</span>
            </div>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div
            class="text-center py-24 bg-white dark:bg-white/5 border-2 border-dashed border-gray-300 dark:border-white/10 rounded-3xl">
            <span
                class="material-symbols-outlined text-8xl text-gray-200 dark:text-gray-800 mb-6">shopping_cart_checkout</span>
            <p class="text-gray-400 font-black uppercase tracking-widest mb-2">No orders found</p>
            <p class="text-sm text-gray-500">Orders will appear here once customers make purchases</p>
        </div>
    <?php else: ?>
        <!-- Orders Table -->
        <div
            class="bg-white dark:bg-white/5 rounded-3xl border-2 border-gray-300 dark:border-white/10 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/5 border-b-2 border-gray-300 dark:border-white/10">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order ID
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer
                                Details</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                Amount</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-black text-primary group-hover:underline">
                                            #<?php echo e(substr($order['order_number'], -8)); ?>
                                        </span>
                                    </div>
                                    <p class="text-[10px] font-bold text-gray-400 mt-1"><?php echo e($order['order_number']); ?>
                                    </p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="size-10 rounded-2xl bg-gradient-to-br from-primary/10 to-primary/5 dark:from-white/10 dark:to-white/5 flex items-center justify-center text-primary dark:text-white text-sm font-black shadow-inner">
                                            <?php echo strtoupper(substr($order['billing_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-[#0f0e1b] dark:text-white">
                                                <?php echo e($order['billing_name']); ?>
                                            </div>
                                            <div class="text-[11px] font-bold text-gray-400 mt-0.5">
                                                <?php echo e($order['billing_email']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-base font-black text-[#0f0e1b] dark:text-white">
                                        <?php echo formatPrice($order['final_amount']); ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php
                                    $statusConfig = [
                                        'completed' => ['color' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400', 'dot' => 'bg-green-500'],
                                        'pending' => ['color' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400', 'dot' => 'bg-yellow-500'],
                                        'failed' => ['color' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400', 'dot' => 'bg-red-500'],
                                        'refunded' => ['color' => 'bg-gray-100 text-gray-700 dark:bg-white/10 dark:text-gray-400', 'dot' => 'bg-gray-400']
                                    ];
                                    $current = $statusConfig[$order['payment_status']] ?? $statusConfig['pending'];
                                    ?>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo $current['color']; ?>">
                                        <span class="size-1.5 rounded-full <?php echo $current['dot']; ?>"></span>
                                        <?php echo $order['payment_status']; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase">
                                        <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                    </div>
                                    <div class="text-[10px] font-black text-gray-400 mt-0.5 italic">
                                        <?php echo date('H:i A', strtotime($order['created_at'])); ?>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="<?php echo baseUrl('admin/orders/view.php?id=' . $order['id']); ?>"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 rounded-xl text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-100 hover:bg-primary hover:text-white hover:border-primary hover:scale-[1.05] active:scale-95 transition-all duration-300 shadow-sm leading-none">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>