<?php
/**
 * Admin - Affiliates List
 */

$pageTitle = 'Manage Affiliates';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../models/Affiliate.php';

$affiliateModel = new Affiliate();

// Handle Status Updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        $affiliateModel->updateStatus($id, 'active');
        setFlashMessage('success', 'Affiliate approved!');
    } elseif ($action === 'reject') {
        $affiliateModel->updateStatus($id, 'rejected');
        setFlashMessage('success', 'Affiliate rejected.');
    }
    redirect(baseUrl('admin/affiliates/list.php'));
}

$affiliates = $affiliateModel->getAllAffiliates();
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">Affiliate Partners</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                <?php echo count($affiliates); ?> Registered Partners
            </p>
        </div>
    </div>

    <!-- Affiliates List -->
    <div class="bg-white dark:bg-white/5 rounded-2xl border-2 border-gray-300 dark:border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50 dark:bg-white/5 border-b border-gray-100 dark:border-white/10 text-xs uppercase text-gray-500 font-bold tracking-wider">
                        <th class="py-4 px-6">Partner</th>
                        <th class="py-4 px-6">Referral Code</th>
                        <th class="py-4 px-6">Rate</th>
                        <th class="py-4 px-6">Balance</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($affiliates)): ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                <p>No affiliates registered yet.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($affiliates as $aff): ?>
                            <tr
                                class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-[#0f0e1b] dark:text-white">
                                        <?php echo e($aff['full_name']); ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?php echo e($aff['email']); ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <code
                                        class="px-2 py-1 bg-gray-100 dark:bg-black/20 rounded text-primary font-mono text-sm"><?php echo e($aff['referral_code']); ?></code>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-400">
                                    <?php echo $aff['commission_rate']; ?>%
                                </td>
                                <td class="py-4 px-6 font-bold text-[#0f0e1b] dark:text-white">
                                    $
                                    <?php echo number_format($aff['balance'], 2); ?>
                                </td>
                                <td class="py-4 px-6">
                                    <?php
                                    $statusColor = match ($aff['status']) {
                                        'active' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    };
                                    ?>
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-bold <?php echo $statusColor; ?> uppercase">
                                        <?php echo $aff['status']; ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <?php if ($aff['status'] === 'pending'): ?>
                                        <a href="?action=approve&id=<?php echo $aff['id']; ?>"
                                            class="text-green-600 hover:underline font-bold text-xs uppercase mr-3">Approve</a>
                                        <a href="?action=reject&id=<?php echo $aff['id']; ?>"
                                            class="text-red-500 hover:underline font-bold text-xs uppercase">Reject</a>
                                    <?php elseif ($aff['status'] === 'active'): ?>
                                        <a href="?action=reject&id=<?php echo $aff['id']; ?>"
                                            class="text-red-500 hover:underline font-bold text-xs uppercase">Ban</a>
                                    <?php elseif ($aff['status'] === 'rejected'): ?>
                                        <a href="?action=approve&id=<?php echo $aff['id']; ?>"
                                            class="text-green-600 hover:underline font-bold text-xs uppercase">Reactivate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>