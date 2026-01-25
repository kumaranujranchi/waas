<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Affiliate.php';

// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Require Login (redirect if not logged in)
if (!isset($_SESSION['user_id'])) {
    redirect(baseUrl('affiliate/login.php'));
}

$userId = $_SESSION['user_id'];
$affiliateModel = new Affiliate();
$affiliate = $affiliateModel->getAffiliateByUserId($userId);

// 2. Check Affiliate Status
if (!$affiliate) {
    // Not an affiliate yet -> Redirect to registration
    redirect(baseUrl('affiliate-register.php'));
}

// 3. Fetch Data (Only if affiliate exists)
$stats = $affiliateModel->getStats($affiliate['id']);
$referrals = $affiliateModel->getReferrals($affiliate['id']);

// Generate Link
$referralLink = baseUrl("?ref=" . $affiliate['referral_code']);

// 4. Output HTML (Header included LAST)
$pageTitle = 'Affiliate Dashboard';
$isAffiliatePortal = true; // Flag to hide standard menus
require_once __DIR__ . '/../includes/header.php';
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
                <!-- Link Generator (Source Tracking) -->
                <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-200 dark:border-white/10 mt-6">
                    <h3 class="font-bold text-[#0f0e1b] dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">campaign</span>
                        Campaign Tracking
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Traffic Source</label>
                            <select id="source-select" onchange="updateLink()"
                                class="w-full mt-1 px-4 py-2 rounded-lg border-gray-200 dark:border-white/10 dark:bg-white/5 text-sm">
                                <option value="">Default (No Source)</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="twitter">Twitter</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="email">Email Campaign</option>
                                <option value="custom">Custom...</option>
                            </select>
                            <input type="text" id="custom-source" placeholder="Enter custom source..."
                                onkeyup="updateLink()"
                                class="w-full mt-2 px-4 py-2 rounded-lg border-gray-200 dark:border-white/10 dark:bg-white/5 text-sm hidden">
                        </div>
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
                                    <th class="py-3 px-2">Source</th>
                                    <th class="py-3 px-2">Payment Status</th>
                                    <th class="py-3 px-2 text-right">Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($referrals)): ?>
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-gray-500">
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
                                                <?php if (!empty($ref['source'])): ?>
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        <?php echo e($ref['source']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200 dark:bg-white/5 dark:border-white/10 dark:text-gray-400">
                                                        Direct
                                                    </span>
                                                <?php endif; ?>
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
    const baseUrl = "<?php echo $referralLink; ?>";

    function updateLink() {
        const sourceSelect = document.getElementById('source-select');
        const customInput = document.getElementById('custom-source');
        let source = sourceSelect.value;

        if (source === 'custom') {
            customInput.classList.remove('hidden');
            source = customInput.value.trim();
        } else {
            customInput.classList.add('hidden');
        }

        const linkElement = document.getElementById('ref-link');
        let newLink = baseUrl;

        if (source) {
            // Encode source safely
            newLink += '&source=' + encodeURIComponent(source);
        }

        linkElement.innerText = newLink;
    }

    function copyLink() {
        const link = document.getElementById('ref-link').innerText;
        navigator.clipboard.writeText(link).then(() => {
            const originalText = document.querySelector('button[onclick="copyLink()"] span:last-child').innerText;
            const btn = document.querySelector('button[onclick="copyLink()"]');
            btn.innerHTML = '<span class="material-symbols-outlined">check</span> Copied!';
            setTimeout(() => {
                btn.innerHTML = '<span class="material-symbols-outlined">content_copy</span> ' + originalText;
            }, 2000);
        });
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>