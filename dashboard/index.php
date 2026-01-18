<?php
/**
 * User Dashboard
 */

$pageTitle = 'Dashboard | WaaS Marketplace';
include __DIR__ . '/../includes/header.php';

// Require login
requireLogin();

require_once __DIR__ . '/../models/Subscription.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Consultation.php';

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

<main class="flex-1 bg-background-light dark:bg-background-dark py-12 px-6">
    <div class="max-w-[1200px] mx-auto">
        <!-- Welcome Section -->
        <div class="mb-12">
            <h1 class="text-4xl font-black text-[#0f0e1b] dark:text-white mb-2">
                Welcome back,
                <?php echo e(explode(' ', $user['full_name'])[0]); ?>!
            </h1>
            <p class="text-[#545095] dark:text-gray-400">Manage your subscriptions and account settings</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Active Subscriptions</p>
                        <p class="text-3xl font-black text-primary">
                            <?php echo count(array_filter($subscriptions, fn($s) => $s['subscription_status'] === 'active')); ?>
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-4xl text-primary">subscriptions</span>
                </div>
            </div>

            <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Orders</p>
                        <p class="text-3xl font-black text-accent-green">
                            <?php echo count($orders); ?>
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-4xl text-accent-green">shopping_bag</span>
                </div>
            </div>

            <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Consultations</p>
                        <p class="text-3xl font-black text-purple-600">
                            <?php echo count($consultations); ?>
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-4xl text-purple-600">event</span>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-6">Active Subscriptions</h2>

            <?php if (empty($subscriptions)): ?>
                <div
                    class="bg-white dark:bg-white/5 rounded-xl p-12 text-center border border-[#e8e8f3] dark:border-white/10">
                    <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">subscriptions</span>
                    <p class="text-gray-500 mb-4">No active subscriptions</p>
                    <a href="<?php echo baseUrl('index.php'); ?>"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:opacity-90">
                        Browse Solutions
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($subscriptions as $subscription): ?>
                        <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                                        <?php echo e($subscription['product_name']); ?>
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <?php echo e($subscription['plan_name']); ?> Plan
                                    </p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold <?php echo $subscription['subscription_status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo ucfirst($subscription['subscription_status']); ?>
                                </span>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Price:</span>
                                    <span class="font-bold">
                                        <?php echo formatPrice($subscription['price']); ?>/
                                        <?php echo $subscription['billing_cycle']; ?>mo
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Start Date:</span>
                                    <span class="font-medium">
                                        <?php echo formatDate($subscription['start_date']); ?>
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">End Date:</span>
                                    <span class="font-medium">
                                        <?php echo formatDate($subscription['end_date']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="<?php echo baseUrl('product-detail.php?slug=' . $subscription['product_slug']); ?>"
                                    class="flex-1 text-center px-4 py-2 border border-gray-300 dark:border-white/10 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-white/5">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Orders -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white mb-6">Recent Orders</h2>

            <?php if (empty($orders)): ?>
                <div
                    class="bg-white dark:bg-white/5 rounded-xl p-8 text-center border border-[#e8e8f3] dark:border-white/10">
                    <p class="text-gray-500">No orders yet</p>
                </div>
            <?php else: ?>
                <div
                    class="bg-white dark:bg-white/5 rounded-xl overflow-hidden border border-[#e8e8f3] dark:border-white/10">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <?php echo e($order['order_number']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <?php echo formatDate($order['created_at']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold">
                                        <?php echo formatPrice($order['final_amount']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-bold <?php echo $order['payment_status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
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

<?php include __DIR__ . '/../includes/footer.php'; ?>