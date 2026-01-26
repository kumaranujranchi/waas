<?php
/**
 * Admin - View Affiliate Details
 */

$pageTitle = 'Affiliate Details';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../models/Affiliate.php';

$affiliateModel = new Affiliate();
$affId = $_GET['id'] ?? null;

if (!$affId) {
    setFlashMessage('error', 'Affiliate ID is required');
    redirect(baseUrl('admin/affiliates/list.php'));
}

$aff = $affiliateModel->getAffiliateById($affId);

if (!$aff) {
    setFlashMessage('error', 'Affiliate not found');
    redirect(baseUrl('admin/affiliates/list.php'));
}

// Handle Payout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'payout') {
    $amount = floatval($_POST['amount'] ?? 0);
    $method = $_POST['method'] ?? 'manual';
    $txId = $_POST['transaction_id'] ?? null;
    $notes = $_POST['notes'] ?? null;

    if ($amount > 0 && $amount <= $aff['balance']) {
        if ($affiliateModel->recordPayout($affId, $amount, $method, $txId, $notes)) {
            setFlashMessage('success', 'Payout recorded successfully!');
            // Refresh data
            $aff = $affiliateModel->getAffiliateById($affId);
        } else {
            setFlashMessage('error', 'Failed to record payout.');
        }
    } else {
        setFlashMessage('error', 'Invalid amount.');
    }
}

// Handle Commission Rate Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_rate') {
    $rate = floatval($_POST['rate'] ?? 20);
    $affiliateModel->updateCommissionRate($affId, $rate);
    setFlashMessage('success', 'Commission rate updated!');
    $aff = $affiliateModel->getAffiliateById($affId);
}

$referrals = $affiliateModel->getReferralsDetailed($affId);
$payouts = $affiliateModel->getPayouts($affId);

$totalEarned = 0;
$convertedCount = 0;
foreach ($referrals as $ref) {
    if ($ref['status'] === 'paid') {
        $totalEarned += floatval($ref['commission_amount']);
        $convertedCount++;
    }
}
?>

<div class="p-8">
    <!-- Breadcrumbs -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex text-sm font-bold uppercase tracking-widest text-gray-400 mb-2">
                <a href="<?php echo baseUrl('admin/affiliates/list.php'); ?>" class="hover:text-primary">Affiliates</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-white">Profile</span>
            </nav>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white">
                <?php echo e($aff['full_name']); ?>
            </h1>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo baseUrl('admin/affiliates/list.php'); ?>"
                class="px-5 py-2.5 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-xs font-black uppercase tracking-widest">Back</a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Current Balance</p>
            <p class="text-3xl font-black text-primary">$
                <?php echo number_format($aff['balance'], 2); ?>
            </p>
        </div>
        <div class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Referrals</p>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white">
                <?php echo count($referrals); ?>
            </p>
        </div>
        <div class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Conversions</p>
            <p class="text-3xl font-black text-accent-green">
                <?php echo $convertedCount; ?>
            </p>
        </div>
        <div class="bg-white dark:bg-white/5 p-6 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Earned</p>
            <p class="text-3xl font-black text-[#0f0e1b] dark:text-white">$
                <?php echo number_format($totalEarned, 2); ?>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: History -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Referrals -->
            <div
                class="bg-white dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:border-white/10">
                    <h3
                        class="text-sm font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">group</span>
                        Referral History
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 dark:bg-white/5 border-b border-gray-100 dark:border-white/10 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                <th class="py-4 px-6">User</th>
                                <th class="py-4 px-6">Joined</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6 text-right">Commission</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            <?php foreach ($referrals as $ref): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-sm text-[#0f0e1b] dark:text-white">
                                            <?php echo e($ref['full_name']); ?>
                                        </div>
                                        <div class="text-[10px] text-gray-500">
                                            <?php echo e($ref['email']); ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500">
                                        <?php echo date('M d, Y', strtotime($ref['created_at'])); ?>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-black uppercase <?php echo $ref['status'] === 'paid' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'; ?>">
                                            <?php echo $ref['status']; ?>
                                        </span>
                                    </td>
                                    <td
                                        class="py-4 px-6 text-right font-black text-sm <?php echo $ref['commission_amount'] > 0 ? 'text-primary' : 'text-gray-300'; ?>">
                                        $
                                        <?php echo number_format($ref['commission_amount'], 2); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payout History -->
            <div
                class="bg-white dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:border-white/10">
                    <h3
                        class="text-sm font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">payments</span>
                        Payout History
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 dark:bg-white/5 border-b border-gray-100 dark:border-white/10 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                <th class="py-4 px-6">Date</th>
                                <th class="py-4 px-6">Amount</th>
                                <th class="py-4 px-6">Method</th>
                                <th class="py-4 px-6">TX ID</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            <?php foreach ($payouts as $p): ?>
                                <tr>
                                    <td class="py-4 px-6 text-xs text-gray-500">
                                        <?php echo date('M d, Y', strtotime($p['created_at'])); ?>
                                    </td>
                                    <td class="py-4 px-6 font-black text-sm text-red-500">
                                        -$
                                        <?php echo number_format($p['amount'], 2); ?>
                                    </td>
                                    <td class="py-4 px-6 text-xs uppercase font-bold text-gray-500">
                                        <?php echo e($p['payment_method']); ?>
                                    </td>
                                    <td class="py-4 px-6 text-xs font-mono text-gray-400">
                                        <?php echo e($p['transaction_id'] ?: 'N/A'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Management -->
        <div class="space-y-8">
            <!-- Record Payout Form -->
            <div class="bg-white dark:bg-white/5 p-8 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                <h3 class="text-sm font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white mb-6">Record
                    Payout</h3>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="payout">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Amount
                            ($)</label>
                        <input type="number" name="amount" step="0.01" max="<?php echo $aff['balance']; ?>"
                            class="w-full bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold"
                            required>
                    </div>
                    <div>
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Method</label>
                        <select name="method"
                            class="w-full bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold">
                            <option value="bank">Bank Transfer</option>
                            <option value="upi">UPI / GPay</option>
                            <option value="paypal">PayPal</option>
                            <option value="manual">Other / Manual</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Transaction
                            ID (Optional)</label>
                        <input type="text" name="transaction_id"
                            class="w-full bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold">
                    </div>
                    <button type="submit"
                        class="w-full py-4 bg-primary text-white rounded-xl font-black uppercase tracking-widest text-xs shadow-lg shadow-primary/30 transition-all active:scale-95">Record
                        Payment</button>
                    <p class="text-[10px] text-gray-400 text-center">Recording a payout will deduct the amount from
                        their balance.</p>
                </form>
            </div>

            <!-- Settings -->
            <div class="bg-white dark:bg-white/5 p-8 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                <h3 class="text-sm font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white mb-6">Settings
                </h3>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="update_rate">
                    <div>
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Commission
                            Rate (%)</label>
                        <input type="number" name="rate" value="<?php echo $aff['commission_rate']; ?>" step="0.5"
                            class="w-full bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm font-bold"
                            required>
                    </div>
                    <button type="submit"
                        class="w-full py-4 bg-white dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 text-[#0f0e1b] dark:text-white rounded-xl font-black uppercase tracking-widest text-xs hover:bg-gray-50 transition-all">Update
                        Rate</button>
                </form>

                <div class="mt-8 pt-8 border-t border-gray-100 dark:border-white/5 space-y-3">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Account Info</p>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500">Referral Code:</span>
                        <code class="font-mono font-bold text-primary"><?php echo e($aff['referral_code']); ?></code>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500">Status:</span>
                        <span class="font-bold uppercase">
                            <?php echo $aff['status']; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>