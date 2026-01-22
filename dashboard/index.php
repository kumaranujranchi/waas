<?php
/**
 * User Dashboard
 */

$pageTitle = 'Dashboard Overview';
include __DIR__ . '/includes/header.php';

// Require login
requireLogin();

require_once __DIR__ . '/../models/Subscription.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Consultation.php';
require_once __DIR__ . '/../models/User.php';

$subscriptionModel = new Subscription();
$orderModel = new Order();
$consultationModel = new Consultation();

$userId = getCurrentUserId();
$user = getCurrentUser();

// Get user data
$subscriptions = $subscriptionModel->getUserSubscriptions($userId);
$orders = $orderModel->getUserOrders($userId);
$consultations = $consultationModel->getBookingsByUser($userId);
?>

<!-- Welcome Section -->
<div class="max-w-[1200px] mx-auto px-6 py-12">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-[#0f0e1b] dark:text-white mb-2">
            Welcome back, <?php echo e(explode(' ', $user['full_name'])[0]); ?>!
        </h1>
        <p class="text-[#545095] dark:text-white/60">Manage your subscriptions and account here</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <a href="<?php echo baseUrl('dashboard/subscriptions.php'); ?>" class="bg-white dark:bg-white/5 rounded-xl p-6 border-2 border-gray-300 dark:border-white/10 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-3xl">subscriptions</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-primary bg-primary/5 px-2 py-1 rounded-lg">Active</span>
            </div>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                <?php echo count(array_filter($subscriptions, fn($s) => $s['subscription_status'] === 'active')); ?>
            </p>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Subscriptions</p>
        </a>

        <a href="<?php echo baseUrl('dashboard/orders.php'); ?>" class="bg-white dark:bg-white/5 rounded-xl p-6 border-2 border-gray-300 dark:border-white/10 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-accent-green/10 rounded-xl flex items-center justify-center text-accent-green">
                    <span class="material-symbols-outlined text-3xl">shopping_bag</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-accent-green bg-accent-green/5 px-2 py-1 rounded-lg">Orders</span>
            </div>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                <?php echo count($orders); ?>
            </p>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Orders</p>
        </a>

        <a href="<?php echo baseUrl('dashboard/billing.php'); ?>" class="bg-white dark:bg-white/5 rounded-xl p-6 border-2 border-gray-300 dark:border-white/10 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-600">
                    <span class="material-symbols-outlined text-3xl">payments</span>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-purple-600 bg-purple-500/5 px-2 py-1 rounded-lg">Billing</span>
            </div>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                <?php echo formatPrice(array_sum(array_column($orders, 'final_amount'))); ?>
            </p>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Spent</p>
        </a>
    </div>


    <!-- Active Subscriptions Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-black text-[#0f0e1b] dark:text-white">Active Subscriptions</h2>
            <a href="<?php echo baseUrl('dashboard/subscriptions.php'); ?>" class="text-primary hover:underline font-bold text-sm flex items-center gap-1">
                View All <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        <?php if (empty($subscriptions)): ?>
            <div class="bg-white dark:bg-white/5 rounded-xl p-12 text-center border-2 border-gray-300 dark:border-white/10 shadow-sm">
                <span class="material-symbols-outlined text-6xl text-gray-300 mb-4 inline-block">subscriptions</span>
                <p class="text-[#545095] dark:text-white/60 mb-6 font-medium">No active subscriptions yet</p>
                <a href="<?php echo baseUrl('index.php'); ?>"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold shadow-lg hover:shadow-primary/30 transition-all">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    Browse Solutions
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach (array_slice($subscriptions, 0, 4) as $subscription): ?>
                    <div class="bg-white dark:bg-white/5 rounded-xl p-6 border-2 border-gray-300 dark:border-white/10 shadow-sm hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-black text-[#0f0e1b] dark:text-white mb-1">
                                    <?php echo e($subscription['product_name']); ?>
                                </h3>
                                <p class="text-sm text-[#545095] dark:text-white/60 font-medium">
                                    <?php echo e($subscription['plan_name']); ?> Plan
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-black bg-accent-green/10 text-accent-green uppercase tracking-wider">
                                Active
                            </span>
                        </div>

                        <div class="space-y-2 mb-4 pb-4 border-b border-gray-200 dark:border-white/10">
                            <div class="flex justify-between text-sm">
                                <span class="text-[#545095] dark:text-white/60 font-medium">Price:</span>
                                <span class="font-black text-[#0f0e1b] dark:text-white"><?php echo formatPrice($subscription['price']); ?> / <?php echo $subscription['billing_cycle']; ?>mo</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-[#545095] dark:text-white/60 font-medium">Next Billing:</span>
                                <span class="font-bold text-[#0f0e1b] dark:text-white"><?php echo formatDate($subscription['end_date']); ?></span>
                            </div>
                        </div>

                        <a href="<?php echo baseUrl('product-detail.php?slug=' . $subscription['product_slug']); ?>"
                            class="text-primary text-sm font-bold hover:underline flex items-center gap-1">
                            View Details <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Orders Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-black text-[#0f0e1b] dark:text-white">Recent Orders</h2>
            <a href="<?php echo baseUrl('dashboard/orders.php'); ?>" class="text-primary hover:underline font-bold text-sm flex items-center gap-1">
                View All <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        <?php if (empty($orders)): ?>
            <div class="bg-white dark:bg-white/5 rounded-xl p-8 text-center border-2 border-gray-300 dark:border-white/10 shadow-sm">
                <span class="material-symbols-outlined text-5xl text-gray-300 mb-3 inline-block">shopping_bag</span>
                <p class="text-[#545095] dark:text-white/60 font-medium">No orders yet</p>
            </div>
        <?php else: ?>
            <div class="bg-white dark:bg-white/5 rounded-xl overflow-hidden border-2 border-gray-300 dark:border-white/10 shadow-sm">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-white/5 border-b-2 border-gray-300 dark:border-white/10">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                        <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-[#0f0e1b] dark:text-white">
                                    <?php echo e($order['order_number']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#545095] dark:text-white/60 font-medium">
                                    <?php echo formatDate($order['created_at']); ?>
                                </td>
                                <td class="px-6 py-4 text-sm font-black text-[#0f0e1b] dark:text-white">
                                    <?php echo formatPrice($order['final_amount']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider <?php echo $order['payment_status'] === 'completed' ? 'bg-accent-green/10 text-accent-green' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>