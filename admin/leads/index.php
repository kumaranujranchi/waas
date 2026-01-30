<?php
/**
 * Admin - Leads List
 */

$pageTitle = 'Chatbot Leads';
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../../models/Lead.php';

$leadModel = new Lead();

// Handle Delete
if (isset($_GET['delete_id'])) {
    if ($leadModel->delete($_GET['delete_id'])) {
        setFlashMessage('Lead deleted successfully', 'success');
    } else {
        setFlashMessage('Failed to delete lead', 'error');
    }
    header('Location: ' . baseUrl('admin/leads/index.php'));
    exit;
}

$leads = $leadModel->getAllLeads();
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">Chatbot Leads</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                <?php echo count($leads); ?> Leads Captured
            </p>
        </div>
    </div>

    <!-- Leads Table -->
    <div
        class="bg-white dark:bg-white/5 rounded-2xl border-2 border-gray-300 dark:border-white/10 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-white/5 border-b-2 border-gray-300 dark:border-white/10">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500">Name</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500">Contact
                            Info</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500">Source</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    <?php foreach ($leads as $lead): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-800 dark:text-white">
                                    <?php echo e($lead['name']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        <?php echo e($lead['email']); ?>
                                    </span>
                                    <span class="text-xs text-gray-500 font-medium">
                                        <?php echo e($lead['phone']); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase rounded-full">
                                    <?php echo e($lead['source']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500">
                                    <?php echo date('M d, Y h:i A', strtotime($lead['created_at'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="?delete_id=<?php echo $lead['id']; ?>"
                                    class="inline-flex size-9 items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm shadow-red-500/10"
                                    onclick="return confirm('Are you sure you want to delete this lead?');">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($leads)): ?>
            <div class="text-center py-24">
                <span class="material-symbols-outlined text-7xl text-gray-200 mb-4">diversity_3</span>
                <p class="text-gray-400 font-black uppercase tracking-widest">No leads captured yet</p>
                <p class="text-xs text-gray-400 mt-2">Leads from the chatbot will appear here.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>