<?php
/**
 * Admin - Consultation Availability Settings
 */

$pageTitle = 'Availability Settings';
include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../../models/Consultation.php';

$consultationModel = new Consultation();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Update Weekly Schedule
    if (isset($_POST['update_schedule'])) {
        $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        
        foreach ($days as $day) {
            $isActive = isset($_POST['active_' . $day]) ? 1 : 0;
            $startTime = $_POST['start_' . $day] ?? '09:00';
            $endTime = $_POST['end_' . $day] ?? '17:00';
            
            $consultationModel->updateAvailability($day, $isActive, $startTime, $endTime);
        }
        setFlashMessage('success', 'Weekly schedule updated successfully.');
        redirect($_SERVER['PHP_SELF']);
    }

    // 2. Add Blocked Date
    if (isset($_POST['add_blocked_date'])) {
        $date = $_POST['blocked_date'];
        $reason = $_POST['blocked_reason'];
        
        if ($date) {
            $consultationModel->addBlockedDate($date, $reason);
            setFlashMessage('success', 'Date blocked successfully.');
            redirect($_SERVER['PHP_SELF']);
        }
    }

    // 3. Delete Blocked Date
    if (isset($_POST['delete_blocked'])) {
        $id = $_POST['blocked_id'];
        $consultationModel->deleteBlockedDate($id);
        setFlashMessage('success', 'Blocked date removed.');
        redirect($_SERVER['PHP_SELF']);
    }
}

$availability = $consultationModel->getAvailabilitySettings();
// Index by day for easier access
$schedule = [];
foreach ($availability as $day) {
    $schedule[$day['day_of_week']] = $day;
}

$blockedDates = $consultationModel->getBlockedDates();
$weekDays = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'];
?>

<div class="p-8 max-w-7xl mx-auto">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
             <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white">Availability Settings</h2>
             <p class="text-gray-500 dark:text-gray-400 mt-1">Configure your working hours and days off</p>
        </div>
         <a href="<?php echo baseUrl('admin/consultations/index.php'); ?>" 
            class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Requests
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Weekly Schedule -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm p-6">
                <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">schedule</span>
                    Weekly Operating Hours
                </h3>

                <form method="POST" action="">
                    <input type="hidden" name="update_schedule" value="1">
                    
                    <div class="space-y-4">
                        <?php foreach ($weekDays as $key => $label): 
                            $dayData = $schedule[$key] ?? ['is_active' => 0, 'start_time' => '09:00', 'end_time' => '17:00'];
                        ?>
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5">
                            <div class="w-32 flex items-center gap-3">
                                <input type="checkbox" name="active_<?php echo $key; ?>" 
                                    class="w-5 h-5 rounded text-primary border-gray-300 focus:ring-primary"
                                    <?php echo $dayData['is_active'] ? 'checked' : ''; ?>>
                                <span class="font-bold text-sm text-gray-700 dark:text-gray-300"><?php echo $label; ?></span>
                            </div>

                            <div class="flex-1 flex items-center gap-4 <?php echo !$dayData['is_active'] ? 'opacity-50 pointer-events-none' : ''; ?>">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400 uppercase font-bold">From</span>
                                    <input type="time" name="start_<?php echo $key; ?>" value="<?php echo date('H:i', strtotime($dayData['start_time'])); ?>"
                                        class="rounded-lg border-gray-200 dark:border-white/10 text-sm py-1 px-2 dark:bg-white/5">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400 uppercase font-bold">To</span>
                                    <input type="time" name="end_<?php echo $key; ?>" value="<?php echo date('H:i', strtotime($dayData['end_time'])); ?>"
                                        class="rounded-lg border-gray-200 dark:border-white/10 text-sm py-1 px-2 dark:bg-white/5">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-primary text-white font-bold py-3 px-8 rounded-xl hover:bg-primary/90 shadow-lg shadow-primary/25 transition-all">
                            Save Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Blocked Dates -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm p-6 sticky top-24">
                <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-500">event_busy</span>
                    Blocked Dates
                </h3>

                <!-- Add New Blocked Date -->
                <form method="POST" action="" class="mb-8">
                    <input type="hidden" name="add_blocked_date" value="1">
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Select Date</label>
                            <input type="date" name="blocked_date" required min="<?php echo date('Y-m-d'); ?>"
                                class="w-full rounded-lg border-gray-200 dark:border-white/10 p-2 dark:bg-white/5">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Reason (Optional)</label>
                            <input type="text" name="blocked_reason" placeholder="e.g. Public Holiday"
                                class="w-full rounded-lg border-gray-200 dark:border-white/10 p-2 dark:bg-white/5">
                        </div>
                        <button type="submit" class="w-full bg-red-50 text-red-600 font-bold py-2 rounded-lg hover:bg-red-100 transition-colors border border-red-100">
                            Block Date
                        </button>
                    </div>
                </form>

                <!-- List of Blocked Dates -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-white/5 pb-2">Upcoming Off Days</h4>
                    
                    <?php if (empty($blockedDates)): ?>
                        <p class="text-sm text-gray-400 italic">No blocked dates set.</p>
                    <?php else: ?>
                        <div class="max-h-[300px] overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                            <?php foreach ($blockedDates as $blocked): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-lg group">
                                <div>
                                    <p class="font-bold text-sm text-gray-800 dark:text-white">
                                        <?php echo date('M d, Y', strtotime($blocked['date'])); ?>
                                    </p>
                                    <?php if($blocked['reason']): ?>
                                        <p class="text-xs text-gray-500"><?php echo e($blocked['reason']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <form method="POST" action="">
                                    <input type="hidden" name="delete_blocked" value="1">
                                    <input type="hidden" name="blocked_id" value="<?php echo $blocked['id']; ?>">
                                    <button type="submit" class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all" onclick="return confirm('Unblock this date?')">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                    </button>
                                </form>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
