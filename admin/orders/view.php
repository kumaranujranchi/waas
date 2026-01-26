<?php
/**
 * Admin - View Order Details
 */

$pageTitle = 'Order Details';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/User.php';

$orderModel = new Order();
$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    setFlashMessage('error', 'Order ID is required');
    redirect(baseUrl('admin/orders/list.php'));
}

$order = $orderModel->getOrderById($orderId);

if (!$order) {
    setFlashMessage('error', 'Order not found');
    redirect(baseUrl('admin/orders/list.php'));
}

$orderItems = $orderModel->getOrderItems($orderId);

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPaymentStatus = $_POST['payment_status'] ?? null;
    $newOrderStatus = $_POST['order_status'] ?? null;
    $updated = false;

    if ($newPaymentStatus) {
        $orderModel->updateOrderStatus($orderId, $newPaymentStatus);
        $updated = true;
    }

    if ($newOrderStatus) {
        $orderModel->updateOperationalStatus($orderId, $newOrderStatus);
        $updated = true;
    }

    if ($updated) {
        setFlashMessage('success', 'Order status updated successfully');
        // Refresh order data
        $order = $orderModel->getOrderById($orderId);
    }
}
?>

<div class="p-8">
    <!-- Breadcrumbs -->
    <div class="mb-8">
        <nav class="flex text-sm font-bold uppercase tracking-widest text-gray-400 mb-4">
            <a href="<?php echo baseUrl('admin/orders/list.php'); ?>"
                class="hover:text-primary transition-colors">Orders</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white">Order Details</span>
        </nav>
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-4xl font-black text-[#0f0e1b] dark:text-white mb-2">Order #
                    <?php echo e(substr($order['order_number'], -8)); ?>
                </h1>
                <p class="text-sm font-bold text-gray-400">Placed on
                    <?php echo date('M d, Y \a\t H:i A', strtotime($order['created_at'])); ?>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo baseUrl('admin/orders/list.php'); ?>"
                    class="px-6 py-3 bg-white dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 rounded-xl text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-100 hover:bg-gray-50 transition-all">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Order Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Order Items -->
            <div
                class="bg-white dark:bg-white/5 rounded-3xl border-2 border-gray-300 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="p-6 border-b-2 border-gray-100 dark:border-white/5 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">shopping_basket</span>
                    <h3 class="text-sm font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white">Order Items
                    </h3>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-white/5 border-b-2 border-gray-100 dark:border-white/5">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    Product</th>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                    Qty</th>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                    Price</th>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-black text-[#0f0e1b] dark:text-white">
                                            <?php echo e($item['product_name']); ?>
                                        </p>
                                        <p class="text-[10px] font-bold text-gray-400 mt-0.5 opacity-70">
                                            <?php echo e($item['plan_name']); ?> Plan
                                        </p>
                                    </td>
                                    <td class="px-8 py-6 text-center text-sm font-bold text-gray-500">
                                        <?php echo e($item['quantity']); ?>
                                    </td>
                                    <td class="px-8 py-6 text-right text-sm font-bold text-gray-500">
                                        <?php echo formatPrice($item['price']); ?>
                                    </td>
                                    <td class="px-8 py-6 text-right text-sm font-black text-[#0f0e1b] dark:text-white">
                                        <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Totals -->
                <div class="p-8 bg-gray-50/50 dark:bg-white/5 border-t-2 border-gray-100 dark:border-white/10">
                    <div class="flex justify-end">
                        <div class="w-full max-w-[240px] space-y-3">
                            <div class="flex justify-between text-sm">
                                <span
                                    class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Subtotal</span>
                                <span class="font-bold text-[#0f0e1b] dark:text-white">
                                    <?php echo formatPrice($order['total_amount']); ?>
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Tax (GST
                                    18%)</span>
                                <span class="font-bold text-[#0f0e1b] dark:text-white">
                                    <?php echo formatPrice($order['tax_amount']); ?>
                                </span>
                            </div>
                            <?php if ($order['discount_amount'] > 0): ?>
                                <div class="flex justify-between text-sm">
                                    <span
                                        class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Discount</span>
                                    <span class="font-bold text-accent-green">-
                                        <?php echo formatPrice($order['discount_amount']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div
                                class="flex justify-between items-center pt-3 border-t-2 border-gray-200 dark:border-white/10">
                                <span class="text-sm font-black uppercase tracking-widest">Total</span>
                                <span class="text-2xl font-black text-primary">
                                    <?php echo formatPrice($order['final_amount']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Info -->
            <div
                class="bg-white dark:bg-white/5 rounded-3xl border-2 border-gray-300 dark:border-white/10 shadow-sm p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                    <h3 class="text-sm font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white">Transaction
                        Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Payment Method
                        </p>
                        <p class="text-sm font-black text-[#0f0e1b] dark:text-white uppercase">
                            <?php echo e(str_replace('_', ' ', $order['payment_method'])); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Transaction ID
                        </p>
                        <p class="text-sm font-black text-[#0f0e1b] dark:text-white break-all">
                            <?php echo e($order['payment_id'] ?: 'N/A'); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Currency</p>
                        <p class="text-sm font-black text-[#0f0e1b] dark:text-white uppercase">
                            <?php echo e($order['currency']); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Last Updated</p>
                        <p class="text-sm font-black text-[#0f0e1b] dark:text-white">
                            <?php echo date('M d, Y H:i A', strtotime($order['updated_at'])); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Status & Customer -->
        <div class="space-y-8">
            <!-- Status Update Form -->
            <div
                class="bg-white dark:bg-white/5 rounded-3xl border-2 border-gray-300 dark:border-white/10 shadow-sm p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-primary">pending_actions</span>
                    <h3 class="text-sm font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white">Update
                        Status</h3>
                </div>
                <form method="POST" class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Payment
                            Status</label>
                        <select name="payment_status"
                            class="w-full bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold text-[#0f0e1b] dark:text-white focus:border-primary outline-none transition-all">
                            <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="completed" <?php echo $order['payment_status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>
                                >Failed</option>
                            <option value="refunded" <?php echo $order['payment_status'] === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Order
                            Status</label>
                        <select name="order_status"
                            class="w-full bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold text-[#0f0e1b] dark:text-white focus:border-primary outline-none transition-all">
                            <option value="pending" <?php echo ($order['order_status'] ?? 'pending') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo ($order['order_status'] ?? '') === 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="completed" <?php echo ($order['order_status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo ($order['order_status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full py-4 bg-primary hover:bg-primary-dark text-white rounded-xl font-black uppercase tracking-widest text-xs shadow-lg shadow-primary/30 transition-all active:scale-95">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Customer Details -->
            <div
                class="bg-white dark:bg-white/5 rounded-3xl border-2 border-gray-300 dark:border-white/10 shadow-sm p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-primary">person</span>
                    <h3 class="text-sm font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white">Customer
                        Details</h3>
                </div>
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="size-16 rounded-2xl bg-gradient-to-br from-primary/10 to-primary/5 dark:from-white/10 dark:to-white/5 flex items-center justify-center text-primary dark:text-white text-xl font-black shadow-inner">
                        <?php echo strtoupper(substr($order['billing_name'] ?: $order['user_name'], 0, 1)); ?>
                    </div>
                    <div>
                        <p class="text-base font-black text-[#0f0e1b] dark:text-white">
                            <?php echo e($order['billing_name'] ?: $order['user_name']); ?>
                        </p>
                        <p class="text-xs font-bold text-gray-400 break-all">
                            <?php echo e($order['billing_email'] ?: $order['user_email']); ?>
                        </p>
                    </div>
                </div>
                <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-white/5">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Account ID</p>
                        <p class="text-xs font-bold text-[#0f0e1b] dark:text-white">USER-
                            <?php echo e(str_pad($order['user_id'], 5, '0', STR_PAD_LEFT)); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Billing Address
                        </p>
                        <p class="text-xs font-bold text-[#0f0e1b] dark:text-white italic">Self-Service Portal Signup
                        </p>
                    </div>
                    <a href="<?php echo baseUrl('admin/users/view.php?id=' . $order['user_id']); ?>"
                        class="flex items-center justify-center gap-2 py-3 bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-200 hover:bg-gray-100 transition-all mt-4">
                        <span class="material-symbols-outlined text-sm">person_search</span>
                        View Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>