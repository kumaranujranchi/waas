<?php
/**
 * Admin - Consultation Calendar (Slot Management)
 */

// Load dependencies FIRST (before any output)
require_once __DIR__ . '/../../models/Consultation.php';
require_once __DIR__ . '/../../includes/functions.php';

// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$consultationModel = new Consultation();

// Handle Add Slot (BEFORE any output/header)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_slot'])) {
    $date = $_POST['date'];
    $startTime = $_POST['start_time'];

    // Calculate end time (1 hour later)
    $endTime = date('H:i', strtotime($startTime) + 3600);

    try {
        $consultationModel->addSlot($date, $startTime, $endTime);
        setFlashMessage('success', 'Slot added successfully');
    } catch (Exception $e) {
        setFlashMessage('error', 'Slot already exists for this time');
    }
    redirect($_SERVER['PHP_SELF']);
}

// Handle Delete Slot (BEFORE any output/header)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_slot'])) {
    $slotId = $_POST['slot_id'];
    $consultationModel->deleteSlot($slotId);
    setFlashMessage('success', 'Slot deleted');
    redirect($_SERVER['PHP_SELF']);
}

// NOW include header (after all redirects are done)
$pageTitle = 'Consultation Calendar';
include __DIR__ . '/../includes/header.php';

// Get next 7 days
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime("+$i days"));
}

// Get all upcoming slots
$allSlots = $consultationModel->getUpcomingSlots(7);

// Group slots by date
$slotsByDate = [];
foreach ($allSlots as $slot) {
    $slotsByDate[$slot['date']][] = $slot;
}
?>

<div class="p-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white">Consultation Calendar</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage available time slots for the next 7 days</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
        <?php foreach ($dates as $date):
            $dayName = date('D', strtotime($date));
            $dayNum = date('j', strtotime($date));
            $monthName = date('M', strtotime($date));
            $isToday = $date === date('Y-m-d');
            $slots = $slotsByDate[$date] ?? [];
            ?>
            <div
                class="bg-white dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden">
                <!-- Date Header -->
                <div
                    class="p-4 <?php echo $isToday ? 'bg-primary text-white' : 'bg-gray-50 dark:bg-white/5'; ?> border-b border-gray-200 dark:border-white/10">
                    <div class="text-center">
                        <p
                            class="text-xs font-bold uppercase tracking-wider <?php echo $isToday ? 'text-white' : 'text-gray-500'; ?>">
                            <?php echo $dayName; ?>
                        </p>
                        <p
                            class="text-2xl font-black <?php echo $isToday ? 'text-white' : 'text-[#0f0e1b] dark:text-white'; ?>">
                            <?php echo $dayNum; ?>
                        </p>
                        <p class="text-xs <?php echo $isToday ? 'text-white/80' : 'text-gray-400'; ?>">
                            <?php echo $monthName; ?>
                        </p>
                    </div>
                </div>

                <!-- Slots List -->
                <div class="p-3 space-y-2 min-h-[200px] max-h-[400px] overflow-y-auto">
                    <?php if (empty($slots)): ?>
                        <p class="text-xs text-gray-400 italic text-center py-4">No slots added</p>
                    <?php else: ?>
                        <?php foreach ($slots as $slot): ?>
                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-white/5 rounded-lg group">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="material-symbols-outlined text-sm <?php echo $slot['is_booked'] ? 'text-red-500' : 'text-green-500'; ?>">
                                        <?php echo $slot['is_booked'] ? 'event_busy' : 'event_available'; ?>
                                    </span>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                        <?php echo date('g:i A', strtotime($slot['start_time'])); ?>
                                    </span>
                                </div>
                                <?php if (!$slot['is_booked']): ?>
                                    <form method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <input type="hidden" name="delete_slot" value="1">
                                        <input type="hidden" name="slot_id" value="<?php echo $slot['id']; ?>">
                                        <button type="submit" class="text-gray-400 hover:text-red-500"
                                            onclick="return confirm('Delete this slot?')">
                                            <span class="material-symbols-outlined text-sm">close</span>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-xs text-red-500 font-bold">Booked</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Add Slot Form -->
                <div class="p-3 border-t border-gray-200 dark:border-white/10">
                    <form method="POST" class="space-y-2">
                        <input type="hidden" name="add_slot" value="1">
                        <input type="hidden" name="date" value="<?php echo $date; ?>">
                        <select name="start_time" required
                            class="w-full text-xs border-gray-200 dark:border-white/10 rounded-lg py-2 px-2 dark:bg-white/5">
                            <option value="">Add Slot</option>
                            <?php for ($h = 10; $h <= 21; $h++): ?>
                                <option value="<?php echo sprintf('%02d:00', $h); ?>">
                                    <?php echo date('g:i A', strtotime(sprintf('%02d:00', $h))); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <button type="submit"
                            class="w-full bg-primary text-white text-xs font-bold py-2 rounded-lg hover:bg-primary/90 transition-all">
                            + Add Slot
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>