<?php
/**
 * Admin Dashboard - Main Page
 */

$pageTitle = 'Dashboard Overview';
include __DIR__ . '/includes/header.php';

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Subscription.php';

$productModel = new Product();
$userModel = new User();
$orderModel = new Order();
$subscriptionModel = new Subscription();

// Get statistics
$totalProducts = $productModel->countProducts();
$totalUsers = count($userModel->getAllUsers());
$totalRevenue = $orderModel->getTotalRevenue();
$activeSubscriptions = count($subscriptionModel->getAllSubscriptions());
?>

<div class="p-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/10 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-3xl">inventory_2</span>
                </div>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-primary bg-primary/5 px-2 py-1 rounded-lg">Inventory</span>
            </div>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                <?php echo $totalProducts; ?>
            </p>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Services</p>
        </div>

        <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/10 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-accent-green/10 rounded-xl flex items-center justify-center text-accent-green">
                    <span class="material-symbols-outlined text-3xl">group</span>
                </div>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-accent-green bg-accent-green/5 px-2 py-1 rounded-lg">Users</span>
            </div>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                <?php echo $totalUsers; ?>
            </p>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Customers</p>
        </div>

        <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/10 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-purple-600/10 rounded-xl flex items-center justify-center text-purple-600">
                    <span class="material-symbols-outlined text-3xl">payments</span>
                </div>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-purple-600 bg-purple-600/5 px-2 py-1 rounded-lg">Finance</span>
            </div>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                <?php echo formatPrice($totalRevenue); ?>
            </p>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Revenue</p>
        </div>

        <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/10 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-orange-600/10 rounded-xl flex items-center justify-center text-orange-600">
                    <span class="material-symbols-outlined text-3xl">subscriptions</span>
                </div>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-orange-600 bg-orange-600/5 px-2 py-1 rounded-lg">Recurring</span>
            </div>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                <?php echo $activeSubscriptions; ?>
            </p>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Subscriptions</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-black text-gray-800 dark:text-white">Quick Management</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <a href="<?php echo baseUrl('admin/products/add.php'); ?>"
            class="bg-white dark:bg-white/5 rounded-2xl p-8 border border-primary/20 hover:border-primary hover:shadow-xl hover:shadow-primary/5 transition-all group overflow-hidden relative">
            <div
                class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 group-hover:opacity-10 transition-all">
                <span class="material-symbols-outlined !text-[120px]">add_circle</span>
            </div>
            <span class="material-symbols-outlined text-5xl text-primary mb-4 block">add_circle</span>
            <h3 class="text-xl font-bold mb-2">Add New Service</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Launch a new solution on the platform</p>
        </a>

        <a href="<?php echo baseUrl('admin/orders/list.php'); ?>"
            class="bg-white dark:bg-white/5 rounded-2xl p-8 border border-gray-100 dark:border-white/10 hover:shadow-lg transition-all group">
            <span class="material-symbols-outlined text-5xl text-accent-green mb-4 block">shopping_cart_checkout</span>
            <h3 class="text-xl font-bold mb-2">Check Orders</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Review pending and completed transactions</p>
        </a>

        <a href="<?php echo baseUrl('admin/users/list.php'); ?>"
            class="bg-white dark:bg-white/5 rounded-2xl p-8 border border-gray-100 dark:border-white/10 hover:shadow-lg transition-all group">
            <span class="material-symbols-outlined text-5xl text-purple-600 mb-4 block">person_search</span>
            <h3 class="text-xl font-bold mb-2">Manage Users</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Update customer profiles and access</p>
        </a>
    </div>

    <!-- Recent Activity -->
    <div
        class="bg-white dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
            <h2 class="text-xl font-black">Recent Orders</h2>
            <a href="<?php echo baseUrl('admin/orders/list.php'); ?>"
                class="text-sm font-bold text-primary hover:underline">View All</a>
        </div>
        <?php
        $recentOrders = $orderModel->getRecentOrders(10);
        if (empty($recentOrders)):
            ?>
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">history</span>
                <p class="text-gray-500 font-bold tracking-tight">No orders yet</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/5">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order #
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        <?php foreach ($recentOrders as $order): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-primary"><?php echo e($order['order_number']); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center text-xs font-black">
                                            <?php echo strtoupper(substr($order['billing_name'], 0, 1)); ?>
                                        </div>
                                        <span class="text-sm font-medium"><?php echo e($order['billing_name']); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black"><?php echo formatPrice($order['final_amount']); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?php echo $order['payment_status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                        <?php echo $order['payment_status']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-400">
                                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>