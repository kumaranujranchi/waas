<?php
/**
 * User Subscriptions Page
 */

$pageTitle = 'My Subscriptions';
include __DIR__ . '/includes/header.php';

require_once __DIR__ . '/../models/Subscription.php';

$subscriptionModel = new Subscription();
$userId = getCurrentUserId();
$subscriptions = $subscriptionModel->getUserSubscriptions($userId);

// Filter by status
$activeSubscriptions = array_filter($subscriptions, fn($s) => $s['subscription_status'] === 'active');
$inactiveSubscriptions = array_filter($subscriptions, fn($s) => $s['subscription_status'] !== 'active');
?>

<!-- Subscriptions Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-2">My Subscriptions</h2>
        <p class="text-gray-600 dark:text-gray-400">Manage all your active and inactive subscriptions</p>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-4 mb-8 border-b border-gray-200 dark:border-white/10">
    <button onclick="showTab('active')" class="tab-button active px-4 py-3 font-medium text-gray-700 dark:text-gray-300 border-b-2 border-primary hover:border-primary transition-colors" data-tab="active">
        Active (<?php echo count($activeSubscriptions); ?>)
    </button>
    <button onclick="showTab('inactive')" class="tab-button px-4 py-3 font-medium text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600 transition-colors" data-tab="inactive">
        Inactive (<?php echo count($inactiveSubscriptions); ?>)
    </button>
</div>

<!-- Active Subscriptions -->
<div id="active-tab" class="tab-content">
    <?php if (empty($activeSubscriptions)): ?>
        <div class="bg-white dark:bg-white/5 rounded-lg p-12 text-center border border-gray-200 dark:border-white/10">
            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4 inline-block">subscriptions</span>
            <p class="text-gray-600 dark:text-gray-400 mb-6">No active subscriptions</p>
            <a href="<?php echo baseUrl('index.php'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary-dark transition-all">
                <span class="material-symbols-outlined">shopping_bag</span>
                Browse Solutions
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($activeSubscriptions as $subscription): ?>
                <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10 hover:shadow-lg transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-1">
                                <?php echo e($subscription['product_name']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <?php echo e($subscription['plan_name']); ?> Plan
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            Active
                        </span>
                    </div>

                    <div class="space-y-3 mb-6 pb-6 border-b border-gray-200 dark:border-white/10">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Price</span>
                            <span class="font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo formatPrice($subscription['price']); ?> / <?php echo $subscription['billing_cycle']; ?> months
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Start Date</span>
                            <span class="font-medium text-[#0f0e1b] dark:text-white">
                                <?php echo formatDate($subscription['start_date']); ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Next Billing</span>
                            <span class="font-medium text-[#0f0e1b] dark:text-white">
                                <?php echo formatDate($subscription['end_date']); ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Days Remaining</span>
                            <span class="font-bold text-primary">
                                <?php echo ceil((strtotime($subscription['end_date']) - time()) / (60 * 60 * 24)); ?> days
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="<?php echo baseUrl('product-detail.php?slug=' . $subscription['product_slug']); ?>" class="flex-1 text-center px-4 py-2 bg-primary/10 text-primary rounded-lg font-medium hover:bg-primary/20 transition-all">
                            View Details
                        </a>
                        <button class="px-4 py-2 border border-red-300 text-red-600 rounded-lg font-medium hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                            Cancel
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Inactive Subscriptions -->
<div id="inactive-tab" class="tab-content hidden">
    <?php if (empty($inactiveSubscriptions)): ?>
        <div class="bg-white dark:bg-white/5 rounded-lg p-12 text-center border border-gray-200 dark:border-white/10">
            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4 inline-block">done_all</span>
            <p class="text-gray-600 dark:text-gray-400">No inactive subscriptions</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($inactiveSubscriptions as $subscription): ?>
                <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10 opacity-75">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-1">
                                <?php echo e($subscription['product_name']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <?php echo e($subscription['plan_name']); ?> Plan
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            <?php echo ucfirst($subscription['subscription_status']); ?>
                        </span>
                    </div>

                    <div class="space-y-3 mb-6 pb-6 border-b border-gray-200 dark:border-white/10">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Price</span>
                            <span class="font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo formatPrice($subscription['price']); ?> / <?php echo $subscription['billing_cycle']; ?> months
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Ended</span>
                            <span class="font-medium text-[#0f0e1b] dark:text-white">
                                <?php echo formatDate($subscription['end_date']); ?>
                            </span>
                        </div>
                    </div>

                    <a href="<?php echo baseUrl('product-detail.php?slug=' . $subscription['product_slug']); ?>" class="block text-center px-4 py-2 bg-primary/10 text-primary rounded-lg font-medium hover:bg-primary/20 transition-all">
                        Renew
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Update button styles
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-primary');
        btn.classList.add('border-transparent');
    });
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('border-primary');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
