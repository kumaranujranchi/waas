<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../models/Affiliate.php';

// Require Login
if (!isset($_SESSION['user_id'])) {
    redirect(baseUrl('auth/login.php?redirect=affiliate/dashboard.php'));
}

$userId = $_SESSION['user_id'];
$affiliateModel = new Affiliate();
$affiliate = $affiliateModel->getAffiliateByUserId($userId);

// If not an affiliate, redirect to register
if (!$affiliate) {
    redirect(baseUrl('affiliate-register.php'));
}

// Get Stats
$stats = $affiliateModel->getStats($affiliate['id']);
$referrals = $affiliateModel->getReferrals($affiliate['id']);

// Generate Link
$referralLink = baseUrl("?ref=" . $affiliate['referral_code']);
?>

<div class="min-h-screen bg-gray-50 dark:bg-[#0f0e1b] py-12">
    <div class="container mx-auto px-4">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white">Affiliate Dashboard</h1>
            <p class="text-gray-500">Welcome back, Partner! Here is your performance overview.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Balance Card -->
            <div
                class="bg-gradient-to-br from-primary to-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-primary/20">
                <div class="flex items-center gap-4 mb-4">
                    <div class="size-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="text-white/80 text-sm font-medium">Available Balance</p>
                        <h3 class="text-3xl font-black">$
                            <?php echo number_format($affiliate['balance'], 2); ?>
                        </h3>
                    </div>
                </div>
                <div class="border-t border-white/20 pt-4 mt-2">
                    <button
                        class="w-full py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        Request Payout
                    </button>
                    <p class="text-xs text-white/60 text-center mt-2">Minimum payout: $50.00</p>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                <div class="flex items-center gap-4">
                    <div
                        class="size-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                        <span class="material-symbols-outlined text-2xl">paid</span>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-bold">Total Earnings</p>
                        <h3 class="text-2xl font-black text-[#0f0e1b] dark:text-white">$
                            <?php echo number_format($stats['total_earnings'], 2); ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Referrals -->
            <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                <div class="flex items-center gap-4">
                    <div
                        class="size-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <span class="material-symbols-outlined text-2xl">group</span>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-bold">Total Referrals</p>
                        <h3 class="text-2xl font-black text-[#0f0e1b] dark:text-white">
                            <?php echo $stats['total_referrals']; ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column: Link & Tools -->
            <div class="space-y-8">
                <!-- Referral Link -->
                <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                    <h3 class="font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">link</span>
                        Your Referral Link
                    </h3>
                    <div
                        class="bg-gray-50 dark:bg-black/20 p-4 rounded-xl border border-dashed border-gray-300 dark:border-white/10">
                        <code class="text-primary font-mono text-sm break-all"
                            id="ref-link"><?php echo $referralLink; ?></code>
                    </div>
                    <button onclick="copyLink()"
                        class="w-full mt-4 py-3 bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#0f0e1b] dark:text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">content_copy</span>
                        Copy Link
                    </button>

                    <div class="mt-4 flex gap-2">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($referralLink); ?>&text=Check%20out%20this%20awesome%20platform!"
                            target="_blank"
                            class="flex-1 py-2 bg-[#1DA1F2]/10 text-[#1DA1F2] rounded-lg flex items-center justify-center hover:bg-[#1DA1F2]/20 transition-all">
                            <span class="material-symbols-outlined">share</span> Twitter
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($referralLink); ?>"
                            target="_blank"
                            class="flex-1 py-2 bg-[#4267B2]/10 text-[#4267B2] rounded-lg flex items-center justify-center hover:bg-[#4267B2]/20 transition-all">
                            <span class="material-symbols-outlined">share</span> Facebook
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Referrals -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                    <h3 class="font-bold text-[#0f0e1b] dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400">history</span>
                        Recent Referrals
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="border-b border-gray-100 dark:border-white/5 text-xs uppercase text-gray-400">
                                    <th class="py-3 px-2">User</th>
                                    <th class="py-3 px-2">Date</th>
                                    <th class="py-3 px-2">Status</th>
                                    <th class="py-3 px-2 text-right">Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($referrals)): ?>
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-gray-500">
                                            <span
                                                class="material-symbols-outlined text-4xl mb-2 opacity-50">person_off</span>
                                            <p>No referrals yet. Share your link to get started!</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($referrals as $ref): ?>
                                        <tr
                                            class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                            <td class="py-4 px-2">
                                                <div class="font-bold text-[#0f0e1b] dark:text-white">
                                                    <?php echo substr($ref['full_name'], 0, 1) . '****' . substr($ref['full_name'], -1); ?>
                                                </div>
                                            </td>
                                            <td class="py-4 px-2 text-sm text-gray-500">
                                                <?php echo date('M j, Y', strtotime($ref['created_at'])); ?>
                                            </td>
                                            <td class="py-4 px-2">
                                                <?php
                                                $badgeColor = match ($ref['status']) {
                                                    'paid' => 'bg-green-100 text-green-700',
                                                    'approved' => 'bg-blue-100 text-blue-700',
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'rejected' => 'bg-red-100 text-red-700',
                                                };
                                                ?>
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs font-bold <?php echo $badgeColor; ?> uppercase">
                                                    <?php echo $ref['status']; ?>
                                                </span>
                                            </td>
                                            <td class="py-4 px-2 text-right font-bold text-[#0f0e1b] dark:text-white">
                                                <?php echo $ref['commission_amount'] > 0 ? '$' . number_format($ref['commission_amount'], 2) : '-'; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function copyLink() {
        const link = document.getElementById('ref-link').innerText;
        navigator.clipboard.writeText(link).then(() => {
            alert('Link copied to clipboard!');
        });
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>