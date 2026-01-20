<?php
/**
 * Admin - Orders List
 */

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/User.php';

$orderModel = new Order();
$orders = $orderModel->getAllOrders();

$pageTitle = 'Manage Orders';
include __DIR__ . '/../includes/header.php';
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">Manage Orders</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                <?php echo count($orders); ?> Total Orders
            </p>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div
            class="text-center py-24 bg-white dark:bg-white/5 border border-dashed border-gray-200 dark:border-white/10 rounded-2xl">
            <span class="material-symbols-outlined text-7xl text-gray-200 mb-4">shopping_cart</span>
            <p class="text-gray-400 font-black uppercase tracking-widest mb-2">No orders found</p>
            <p class="text-sm text-gray-500">Orders will appear here once customers make purchases</p>
        </div>
    <?php else: ?>
        <!-- Orders Table -->
        <div
            class="bg-white dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order #
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Product
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Payment
                                Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order
                                Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-primary">
                                        <?php echo e($order['order_number']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center text-xs font-black">
                                            <?php echo strtoupper(substr($order['billing_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold">
                                                <?php echo e($order['billing_name']); ?>
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                <?php echo e($order['billing_email']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium">
                                        <?php echo e($order['product_name'] ?? 'N/A'); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black">
                                        <?php echo formatPrice($order['final_amount']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $paymentStatusColors = [
                                        'completed' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'failed' => 'bg-red-100 text-red-700',
                                        'refunded' => 'bg-gray-100 text-gray-700'
                                    ];
                                    $statusColor = $paymentStatusColors[$order['payment_status']] ?? 'bg-gray-100 text-gray-700';
                                    ?>
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?php echo $statusColor; ?>">
                                        <?php echo $order['payment_status']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $orderStatusColors = [
                                        'pending' => 'bg-blue-100 text-blue-700',
                                        'processing' => 'bg-purple-100 text-purple-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700'
                                    ];
                                    $orderColor = $orderStatusColors[$order['order_status']] ?? 'bg-gray-100 text-gray-700';
                                    ?>
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?php echo $orderColor; ?>">
                                        <?php echo $order['order_status']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-400">
                                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="<?php echo baseUrl('admin/orders/view.php?id=' . $order['id']); ?>"
                                        class="inline-flex items-center gap-1 px-3 py-2 bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-primary hover:text-white hover:border-primary transition-all">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        View
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