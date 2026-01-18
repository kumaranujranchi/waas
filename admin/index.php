<?php
/**
 * Admin Dashboard - Main Page
 */

$pageTitle = 'Admin Dashboard | WaaS Marketplace';
include __DIR__ . '/../includes/header.php';

// Require admin access
requireAdmin();

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

<main class="flex-1 bg-background-light dark:bg-background-dark min-h-screen">
    <div class="max-w-[1400px] mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-black text-[#0f0e1b] dark:text-white mb-2">Admin Dashboard</h1>
                <p class="text-[#545095] dark:text-gray-400">Manage your marketplace</p>
            </div>
            <a href="<?php echo baseUrl('admin/products/add.php'); ?>"
                class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-lg">
                <span class="material-symbols-outlined">add</span>
                Add New Product
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
                <div class="flex items-center justify-between mb-4">
                    <span class="material-symbols-outlined text-4xl text-primary">inventory_2</span>
                    <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded-full">Products</span>
                </div>
                <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                    <?php echo $totalProducts; ?>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Products</p>
            </div>

            <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
                <div class="flex items-center justify-between mb-4">
                    <span class="material-symbols-outlined text-4xl text-accent-green">group</span>
                    <span
                        class="text-xs font-bold text-accent-green bg-accent-green/10 px-2 py-1 rounded-full">Users</span>
                </div>
                <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                    <?php echo $totalUsers; ?>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
            </div>

            <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
                <div class="flex items-center justify-between mb-4">
                    <span class="material-symbols-outlined text-4xl text-purple-600">payments</span>
                    <span
                        class="text-xs font-bold text-purple-600 bg-purple-600/10 px-2 py-1 rounded-full">Revenue</span>
                </div>
                <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                    <?php echo formatPrice($totalRevenue); ?>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
            </div>

            <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
                <div class="flex items-center justify-between mb-4">
                    <span class="material-symbols-outlined text-4xl text-orange-600">subscriptions</span>
                    <span
                        class="text-xs font-bold text-orange-600 bg-orange-600/10 px-2 py-1 rounded-full">Active</span>
                </div>
                <p class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">
                    <?php echo $activeSubscriptions; ?>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Subscriptions</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <a href="<?php echo baseUrl('admin/products/list.php'); ?>"
                class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10 hover:shadow-lg transition-all group">
                <span
                    class="material-symbols-outlined text-5xl text-primary mb-4 group-hover:scale-110 transition-transform">inventory_2</span>
                <h3 class="text-xl font-bold mb-2">Manage Products</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Add, edit, or delete products</p>
            </a>

            <a href="<?php echo baseUrl('admin/orders/list.php'); ?>"
                class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10 hover:shadow-lg transition-all group">
                <span
                    class="material-symbols-outlined text-5xl text-accent-green mb-4 group-hover:scale-110 transition-transform">shopping_cart</span>
                <h3 class="text-xl font-bold mb-2">View Orders</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage customer orders</p>
            </a>

            <a href="<?php echo baseUrl('admin/users/list.php'); ?>"
                class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10 hover:shadow-lg transition-all group">
                <span
                    class="material-symbols-outlined text-5xl text-purple-600 mb-4 group-hover:scale-110 transition-transform">group</span>
                <h3 class="text-xl font-bold mb-2">Manage Users</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">View and manage users</p>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-white/5 rounded-xl p-6 border border-[#e8e8f3] dark:border-white/10">
            <h2 class="text-2xl font-bold mb-6">Recent Orders</h2>
            <?php
            $recentOrders = $orderModel->getRecentOrders(10);
            if (empty($recentOrders)):
                ?>
                <p class="text-gray-500 text-center py-8">No orders yet</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <?php echo e($order['order_number']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <?php echo e($order['billing_name']); ?>
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
                                    <td class="px-6 py-4 text-sm">
                                        <?php echo formatDate($order['created_at']); ?>
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