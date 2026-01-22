<?php
/**
 * Admin - Users List
 */

$pageTitle = 'Manage Users';
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Subscription.php';

$userModel = new User();
$orderModel = new Order();
$subModel = new Subscription();

$users = $userModel->getAllUsers();
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
        <div>
            <h1 class="text-4xl font-black text-[#0f0e1b] dark:text-white mb-2">Manage Users</h1>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-[0.2em]">
                <?php echo count($users); ?> Total Registered Users
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div
                class="px-4 py-2 bg-white dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 rounded-xl flex items-center gap-2">
                <span class="size-2 rounded-full bg-accent-green animate-pulse"></span>
                <span class="text-xs font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white">Active
                    Database</span>
            </div>
        </div>
    </div>

    <?php if (empty($users)): ?>
        <div
            class="text-center py-24 bg-white dark:bg-white/5 border-2 border-dashed border-gray-300 dark:border-white/10 rounded-3xl">
            <span class="material-symbols-outlined text-8xl text-gray-200 dark:text-gray-800 mb-6">group</span>
            <p class="text-gray-400 font-black uppercase tracking-widest mb-2">No users found</p>
            <p class="text-sm text-gray-500">Users will appear here once they register on the platform</p>
        </div>
    <?php else: ?>
        <!-- Users Table -->
        <div
            class="bg-white dark:bg-white/5 rounded-3xl border-2 border-gray-300 dark:border-white/10 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/5 border-b-2 border-gray-300 dark:border-white/10">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">User
                                Identity</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                Involvement</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                Privilege</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined Date
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                Management</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="size-12 rounded-2xl bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center text-white text-lg font-black shadow-lg group-hover:scale-110 transition-transform">
                                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-black text-[#0f0e1b] dark:text-white group-hover:text-primary transition-colors">
                                                <?php echo e($user['full_name']); ?>
                                            </div>
                                            <div class="text-[11px] font-bold text-gray-400 mt-0.5">
                                                <?php echo e($user['email']); ?>
                                            </div>
                                            <?php if (!empty($user['phone'])): ?>
                                                <div class="text-[10px] font-bold text-gray-300 mt-0.5 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px]">call</span>
                                                    <?php echo e($user['phone']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-6">
                                        <div class="text-center">
                                            <p class="text-sm font-black text-primary">
                                                <?php echo count($orderModel->getUserOrders($user['id'])); ?>
                                            </p>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Orders</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-black text-accent-green">
                                                <?php echo count($subModel->getUserSubscriptions($user['id'])); ?>
                                            </p>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Subs</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/20 dark:text-purple-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400'; ?>">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo $user['status'] === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400'; ?>">
                                        <span
                                            class="size-1.5 rounded-full <?php echo $user['status'] === 'active' ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                                        <?php echo $user['status']; ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-500 dark:text-gray-300 uppercase">
                                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                    </div>
                                    <p class="text-[10px] font-black text-gray-400 mt-0.5">
                                        <?php echo timeAgo($user['created_at']); ?>
                                    </p>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="<?php echo baseUrl('admin/users/view.php?id=' . $user['id']); ?>"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 rounded-xl text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-100 hover:bg-primary hover:text-white hover:border-primary hover:scale-[1.05] active:scale-95 transition-all duration-300 shadow-sm leading-none">
                                        <span class="material-symbols-outlined text-sm">manage_accounts</span>
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