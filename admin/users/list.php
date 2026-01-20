<?php
/**
 * Admin - Users List
 */

$pageTitle = 'Manage Users';
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../../models/User.php';

$userModel = new User();
$users = $userModel->getAllUsers();
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">Manage Users</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                <?php echo count($users); ?> Total Users
            </p>
        </div>
    </div>

    <?php if (empty($users)): ?>
        <div
            class="text-center py-24 bg-white dark:bg-white/5 border border-dashed border-gray-200 dark:border-white/10 rounded-2xl">
            <span class="material-symbols-outlined text-7xl text-gray-200 mb-4">group</span>
            <p class="text-gray-400 font-black uppercase tracking-widest mb-2">No users found</p>
            <p class="text-sm text-gray-500">Users will appear here once they register</p>
        </div>
    <?php else: ?>
        <!-- Users Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($users as $user): ?>
                <div
                    class="bg-white dark:bg-white/5 rounded-2xl border-2 border-gray-300 dark:border-white/10 overflow-hidden hover:shadow-xl transition-all shadow-sm">
                    <div class="p-6">
                        <!-- User Avatar & Info -->
                        <div class="flex items-start gap-4 mb-4">
                            <div
                                class="size-16 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-2xl flex-shrink-0">
                                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-black text-[#0f0e1b] dark:text-white truncate">
                                    <?php echo e($user['full_name']); ?>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    <?php echo e($user['email']); ?>
                                </p>
                                <?php if (!empty($user['phone'])): ?>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <?php echo e($user['phone']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- User Stats -->
                        <div class="grid grid-cols-2 gap-3 mb-4 pt-4 border-t border-gray-100 dark:border-white/5">
                            <div class="text-center">
                                <p class="text-2xl font-black text-primary">
                                    <?php
                                    // Count user's orders
                                    require_once __DIR__ . '/../../models/Order.php';
                                    $orderModel = new Order();
                                    $userOrders = $orderModel->getUserOrders($user['id']);
                                    echo count($userOrders);
                                    ?>
                                </p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Orders</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-black text-accent-green">
                                    <?php
                                    // Count user's subscriptions
                                    require_once __DIR__ . '/../../models/Subscription.php';
                                    $subModel = new Subscription();
                                    $userSubs = $subModel->getUserSubscriptions($user['id']);
                                    echo count($userSubs);
                                    ?>
                                </p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Subscriptions</p>
                            </div>
                        </div>

                        <!-- User Meta -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Role:</span>
                                <span
                                    class="px-2 py-1 rounded-full font-black uppercase tracking-widest <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'; ?>">
                                    <?php echo $user['role']; ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Status:</span>
                                <span
                                    class="px-2 py-1 rounded-full font-black uppercase tracking-widest <?php echo $user['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                    <?php echo $user['status']; ?>
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Joined:</span>
                                <span class="font-bold text-gray-600 dark:text-gray-300">
                                    <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="<?php echo baseUrl('admin/users/view.php?id=' . $user['id']); ?>"
                                class="flex-1 text-center px-4 py-3 bg-gray-50 dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary hover:text-white hover:border-primary transition-all">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>