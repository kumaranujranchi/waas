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
<div class="mb-12">
    <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-2">
        Welcome back, <?php echo e(explode(' ', $user['full_name'])[0]); ?>!
    </h2>
    <p class="text-gray-600 dark:text-gray-400">Manage your subscriptions and account here</p>
</div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <a href="<?php echo baseUrl('dashboard/subscriptions.php'); ?>" class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Active Subscriptions</p>
                        <p class="text-4xl font-bold text-primary">
                            <?php echo count(array_filter($subscriptions, fn($s) => $s['subscription_status'] === 'active')); ?>
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-primary/20">subscriptions</span>
                </div>
            </a>

            <a href="<?php echo baseUrl('dashboard/orders.php'); ?>" class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Total Orders</p>
                        <p class="text-4xl font-bold text-accent-green">
                            <?php echo count($orders); ?>
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-accent-green/20">shopping_bag</span>
                </div>
            </a>

            <a href="<?php echo baseUrl('dashboard/billing.php'); ?>" class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10 hover:shadow-lg transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Total Spent</p>
                        <p class="text-4xl font-bold text-purple-600">
                            <?php echo formatPrice(array_sum(array_column($orders, 'final_amount'))); ?>
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-purple-600/20">payments</span>
                </div>
            </a>


        <!-- Active Subscriptions Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-[#0f0e1b] dark:text-white">Active Subscriptions</h3>
                <a href="<?php echo baseUrl('dashboard/subscriptions.php'); ?>" class="text-primary hover:underline font-medium text-sm">
                    View All
                </a>
            </div>

            <?php if (empty($subscriptions)): ?>
                <div class="bg-white dark:bg-white/5 rounded-lg p-12 text-center border border-gray-200 dark:border-white/10">
                    <span class="material-symbols-outlined text-6xl text-gray-300 mb-4 inline-block">subscriptions</span>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">No active subscriptions yet</p>
                    <a href="<?php echo baseUrl('index.php'); ?>"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary-dark transition-all">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        Browse Solutions
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach (array_slice($subscriptions, 0, 4) as $subscription): ?>
                        <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10 hover:shadow-lg transition-all">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                        <?php echo e($subscription['product_name']); ?>
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <?php echo e($subscription['plan_name']); ?> Plan
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Active
                                </span>
                            </div>

                            <div class="space-y-2 mb-4 pb-4 border-b border-gray-200 dark:border-white/10">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Price:</span>
                                    <span class="font-bold"><?php echo formatPrice($subscription['price']); ?> / <?php echo $subscription['billing_cycle']; ?>mo</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Next Billing:</span>
                                    <span class="font-medium"><?php echo formatDate($subscription['end_date']); ?></span>
                                </div>
                            </div>

                            <a href="<?php echo baseUrl('product-detail.php?slug=' . $subscription['product_slug']); ?>"
                                class="text-primary text-sm font-medium hover:underline">
                                View Details →
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Orders Section -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-[#0f0e1b] dark:text-white">Recent Orders</h3>
                <a href="<?php echo baseUrl('dashboard/orders.php'); ?>" class="text-primary hover:underline font-medium text-sm">
                    View All
                </a>
            </div>

            <?php if (empty($orders)): ?>
                <div class="bg-white dark:bg-white/5 rounded-lg p-8 text-center border border-gray-200 dark:border-white/10">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3 inline-block">shopping_bag</span>
                    <p class="text-gray-600 dark:text-gray-400">No orders yet</p>
                </div>
            <?php else: ?>
                <div class="bg-white dark:bg-white/5 rounded-lg overflow-hidden border border-gray-200 dark:border-white/10">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-[#0f0e1b] dark:text-white">
                                        <?php echo e($order['order_number']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        <?php echo formatDate($order['created_at']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-[#0f0e1b] dark:text-white">
                                        <?php echo formatPrice($order['final_amount']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $order['payment_status'] === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'; ?>">
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