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
    <button onclick="showTab('active')"
        class="tab-button active px-4 py-3 font-medium text-gray-700 dark:text-gray-300 border-b-2 border-primary hover:border-primary transition-colors"
        data-tab="active">
        Active (<?php echo count($activeSubscriptions); ?>)
    </button>
    <button onclick="showTab('inactive')"
        class="tab-button px-4 py-3 font-medium text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600 transition-colors"
        data-tab="inactive">
        Inactive (<?php echo count($inactiveSubscriptions); ?>)
    </button>
</div>

<!-- Active Subscriptions -->
<div id="active-tab" class="tab-content">
    <?php if (empty($activeSubscriptions)): ?>
        <div class="bg-white dark:bg-white/5 rounded-lg p-12 text-center border border-gray-200 dark:border-white/10">
            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4 inline-block">subscriptions</span>
            <p class="text-gray-600 dark:text-gray-400 mb-6">No active subscriptions</p>
            <a href="<?php echo baseUrl('index.php'); ?>"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary-dark transition-all">
                <span class="material-symbols-outlined">shopping_bag</span>
                Browse Solutions
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($activeSubscriptions as $subscription): ?>
                <div
                    class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10 hover:shadow-lg transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-1">
                                <?php echo e($subscription['product_name']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <?php echo e($subscription['plan_name']); ?> Plan
                            </p>
                        </div>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            Active
                        </span>
                    </div>

                    <div class="space-y-3 mb-6 pb-6 border-b border-gray-200 dark:border-white/10">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Price</span>
                            <span class="font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo formatPrice($subscription['price']); ?> /
                                <?php echo $subscription['billing_cycle']; ?> months
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
                        <a href="<?php echo baseUrl('product-detail.php?slug=' . $subscription['product_slug']); ?>"
                            class="flex-1 text-center px-4 py-2 bg-primary/10 text-primary rounded-lg font-medium hover:bg-primary/20 transition-all">
                            View Details
                        </a>
                        <button onclick="cancelSubscription(<?php echo $subscription['id']; ?>)"
                            class="px-4 py-2 border border-red-300 text-red-600 rounded-lg font-medium hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
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
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            <?php echo ucfirst($subscription['subscription_status']); ?>
                        </span>
                    </div>

                    <div class="space-y-3 mb-6 pb-6 border-b border-gray-200 dark:border-white/10">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Price</span>
                            <span class="font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo formatPrice($subscription['price']); ?> /
                                <?php echo $subscription['billing_cycle']; ?> months
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Ended</span>
                            <span class="font-medium text-[#0f0e1b] dark:text-white">
                                <?php echo formatDate($subscription['end_date']); ?>
                            </span>
                        </div>
                    </div>

                    <a href="<?php echo baseUrl('product-detail.php?slug=' . $subscription['product_slug']); ?>"
                        class="block text-center px-4 py-2 bg-primary/10 text-primary rounded-lg font-medium hover:bg-primary/20 transition-all">
                        Renew
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Custom Confirmation Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-[#1a1b2e] rounded-2xl shadow-2xl w-full max-w-md mx-4 transform scale-95 transition-transform duration-300 translate-y-4" id="cancelModalContent">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl text-red-600 dark:text-red-500">warning</span>
            </div>
            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-2">Cancel Subscription?</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm leading-relaxed">
                Are you sure you want to cancel? The plan will remain active until the end of the current billing cycle, but it will not renew.
            </p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeCancelModal()" 
                    class="px-5 py-2.5 rounded-xl font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 transition-all">
                    No, Keep It
                </button>
                <button id="confirmCancelBtn" 
                    class="px-5 py-2.5 rounded-xl font-bold bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">cancel</span>
                    Yes, Cancel It
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentSubscriptionId = null;

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

    function cancelSubscription(subscriptionId) {
        currentSubscriptionId = subscriptionId;
        const modal = document.getElementById('cancelModal');
        const modalContent = document.getElementById('cancelModalContent');
        
        modal.classList.remove('hidden');
        // Small delay to allow display:block to apply before opacity transition
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'translate-y-4');
            modalContent.classList.add('scale-100', 'translate-y-0');
        }, 10);
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancelModal');
        const modalContent = document.getElementById('cancelModalContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100', 'translate-y-0');
        modalContent.classList.add('scale-95', 'translate-y-4');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            currentSubscriptionId = null;
        }, 300);
    }

    document.getElementById('confirmCancelBtn').addEventListener('click', function() {
        if (!currentSubscriptionId) return;

        const button = this;
        const originalContent = button.innerHTML;
        button.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span> Cancelling...';
        button.disabled = true;

        fetch('api/cancel_subscription.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ subscription_id: currentSubscriptionId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeCancelModal();
                // Show success toast or reload
                location.reload(); 
            } else {
                alert('Error: ' + data.message);
                button.innerHTML = originalContent;
                button.disabled = false;
                closeCancelModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
            button.innerHTML = originalContent;
            button.disabled = false;
            closeCancelModal();
        });
    });

    // Close modal on outside click
    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCancelModal();
        }
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>