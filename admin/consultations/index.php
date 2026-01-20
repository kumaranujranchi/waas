<?php
/**
 * Admin - Consultation List
 */

$pageTitle = 'Consultation Requests';
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../models/Consultation.php';

$consultationModel = new Consultation();

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status' && isset($_POST['id'], $_POST['status'])) {
        $consultationModel->updateBookingStatus($_POST['id'], $_POST['status']);
        setFlashMessage('success', 'Status updated successfully');
        redirect($_SERVER['PHP_SELF']);
    }

    if ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $consultationModel->deleteBooking($_POST['id']);
        setFlashMessage('success', 'Request deleted successfully');
        redirect($_SERVER['PHP_SELF']);
    }
}

// Get Bookings
$bookings = $consultationModel->getAllBookings(); // You might want to implement pagination later

function getStatusBadge($status)
{
    switch ($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'contacted':
            return 'bg-blue-100 text-blue-800';
        case 'confirmed':
            return 'bg-green-100 text-green-800';
        case 'completed':
            return 'bg-gray-100 text-gray-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
?>

<div class="p-8">
    <div
        class="bg-white dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden">
        <div
            class="p-6 border-b border-gray-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#0f0e1b] dark:text-white">Consultation Requests</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage and track booking inquiries</p>
            </div>

            <div class="flex gap-2">
                <button onclick="window.location.reload()"
                    class="px-4 py-2 bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-white/20 transition-all text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    Refresh
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5 text-xs font-bold text-gray-500 uppercase tracking-widest">
                        <th class="p-4">Date</th>
                        <th class="p-4">Client</th>
                        <th class="p-4">Business</th>
                        <th class="p-4">Preferred Slot</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                No consultation requests found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-sm whitespace-nowrap">
                                    <span class="font-bold text-[#0f0e1b] dark:text-white">
                                        <?php echo date('M d, Y', strtotime($booking['created_at'])); ?>
                                    </span>
                                    <span class="block text-xs text-gray-400">
                                        <?php echo date('h:i A', strtotime($booking['created_at'])); ?>
                                    </span>
                                </td>

                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase">
                                            <?php echo substr($booking['full_name'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-[#0f0e1b] dark:text-white">
                                                <?php echo e($booking['full_name']); ?>
                                            </p>
                                            <a href="mailto:<?php echo e($booking['email']); ?>"
                                                class="text-xs text-primary hover:underline block">
                                                <?php echo e($booking['email']); ?>
                                            </a>
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                <?php echo e($booking['phone']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <?php echo e($booking['business_type']); ?>
                                    </span>
                                    <?php if ($booking['requirements']): ?>
                                        <div class="group relative mt-1 cursor-help">
                                            <span class="text-xs text-gray-400 underline decoration-dotted">View Requirements</span>
                                            <div
                                                class="hidden group-hover:block absolute left-0 bottom-full mb-2 w-64 p-3 bg-black text-white text-xs rounded-lg shadow-xl z-50">
                                                <?php echo e($booking['requirements']); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="p-4 text-sm">
                                    <?php if ($booking['preferred_date']): ?>
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                            <span class="material-symbols-outlined text-base">calendar_today</span>
                                            <?php echo date('M d, Y', strtotime($booking['preferred_date'])); ?>
                                        </div>
                                        <?php if ($booking['preferred_time']): ?>
                                            <div class="flex items-center gap-2 text-gray-500 text-xs mt-1">
                                                <span class="material-symbols-outlined text-base">schedule</span>
                                                <?php echo date('h:i A', strtotime($booking['preferred_time'])); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">Not specified</span>
                                    <?php endif; ?>
                                </td>

                                <td class="p-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-xs font-bold uppercase tracking-widest <?php echo getStatusBadge($booking['status']); ?>">
                                        <?php echo $booking['status']; ?>
                                    </span>
                                </td>

                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Status Form -->
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-xs border-gray-200 dark:border-white/10 rounded-lg py-1 pl-2 pr-6 focus:ring-primary focus:border-primary bg-white dark:bg-white/5">
                                                <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="contacted" <?php echo $booking['status'] === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                                                <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </form>

                                        <!-- Delete -->
                                        <form method="POST" action="" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this requests?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                            <button type="submit"
                                                class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
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