<?php
/**
 * Admin - Consultation Calendar (Calendly-Style)
 */

// Load dependencies in correct order
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../models/Consultation.php';

// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$consultationModel = new Consultation();

// Handle Add Slot
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_slot'])) {
    $date = $_POST['date'];
    $startTime = $_POST['start_time'];
    $endTime = date('H:i', strtotime($startTime) + 3600);

    try {
        $consultationModel->addSlot($date, $startTime, $endTime);
        echo json_encode(['success' => true, 'message' => 'Slot added successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Slot already exists']);
    }
    exit;
}

// Handle Delete Slot
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_slot'])) {
    $slotId = $_POST['slot_id'];
    $consultationModel->deleteSlot($slotId);
    echo json_encode(['success' => true, 'message' => 'Slot deleted']);
    exit;
}

// Handle Get Slots for Date (AJAX)
if (isset($_GET['action']) && $_GET['action'] === 'get_slots' && isset($_GET['date'])) {
    $date = $_GET['date'];
    $slots = $consultationModel->getSlotsByDate($date);
    echo json_encode(['slots' => $slots]);
    exit;
}

// Get current month/year
$currentMonth = isset($_GET['month']) ? (int) $_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');

// Calculate calendar data
$firstDay = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
$daysInMonth = date('t', $firstDay);
$dayOfWeek = date('w', $firstDay);
$monthName = date('F Y', $firstDay);

// Get all slots for this month
$startDate = date('Y-m-d', $firstDay);
$endDate = date('Y-m-d', mktime(0, 0, 0, $currentMonth, $daysInMonth, $currentYear));
$allSlots = $consultationModel->getUpcomingSlots(60); // Get 60 days worth

// Group by date
$slotsByDate = [];
foreach ($allSlots as $slot) {
    $slotsByDate[$slot['date']][] = $slot;
}

// NOW include header
$pageTitle = 'Consultation Calendar';
include __DIR__ . '/../includes/header.php';
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-[#0f0e1b] dark:text-white">Consultation Calendar</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Click on a date to manage time slots</p>
        </div>

        <!-- Month Navigation -->
        <div class="flex items-center gap-4">
            <a href="?month=<?php echo $currentMonth == 1 ? 12 : $currentMonth - 1; ?>&year=<?php echo $currentMonth == 1 ? $currentYear - 1 : $currentYear; ?>"
                class="p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all">
                <span class="material-symbols-outlined">chevron_left</span>
            </a>
            <span class="text-lg font-bold text-[#0f0e1b] dark:text-white min-w-[150px] text-center">
                <?php echo $monthName; ?>
            </span>
            <a href="?month=<?php echo $currentMonth == 12 ? 1 : $currentMonth + 1; ?>&year=<?php echo $currentMonth == 12 ? $currentYear + 1 : $currentYear; ?>"
                class="p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all">
                <span class="material-symbols-outlined">chevron_right</span>
            </a>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div
        class="bg-white dark:bg-white/5 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 border-b border-gray-200 dark:border-white/10">
            <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day): ?>
                <div class="p-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <?php echo $day; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7">
            <?php
            // Empty cells before first day
            for ($i = 0; $i < $dayOfWeek; $i++) {
                echo '<div class="aspect-square border-r border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5"></div>';
            }

            // Days of month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                $isToday = $date === date('Y-m-d');
                $isPast = strtotime($date) < strtotime('today');
                $slots = $slotsByDate[$date] ?? [];
                $slotCount = count($slots);
                $availableCount = count(array_filter($slots, fn($s) => !$s['is_booked']));
                ?>
                <div class="aspect-square border-r border-b border-gray-100 dark:border-white/5 p-2 hover:bg-gray-50 dark:hover:bg-white/5 transition-all cursor-pointer <?php echo $isPast ? 'opacity-50' : ''; ?>"
                    onclick="<?php echo !$isPast ? "openSlotModal('$date')" : ''; ?>">
                    <div class="h-full flex flex-col">
                        <div class="flex items-center justify-between mb-1">
                            <span
                                class="text-sm font-bold <?php echo $isToday ? 'text-primary' : 'text-gray-700 dark:text-gray-300'; ?>">
                                <?php echo $day; ?>
                            </span>
                            <?php if ($slotCount > 0): ?>
                                <span
                                    class="text-xs px-1.5 py-0.5 rounded-full <?php echo $availableCount > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                    <?php echo $availableCount; ?>/<?php echo $slotCount; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if ($slotCount > 0): ?>
                            <div class="flex-1 overflow-hidden">
                                <?php foreach (array_slice($slots, 0, 2) as $slot): ?>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                        <?php echo date('g:i A', strtotime($slot['start_time'])); ?>
                                    </div>
                                <?php endforeach; ?>
                                <?php if ($slotCount > 2): ?>
                                    <div class="text-xs text-gray-400">+<?php echo $slotCount - 2; ?> more</div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Slot Management Modal -->
<div id="slotModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50"
    onclick="closeSlotModal(event)">
    <div class="bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-2xl max-w-md w-full mx-4"
        onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white" id="modalDate"></h3>
                <button onclick="closeSlotModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>

        <div class="p-6 max-h-[400px] overflow-y-auto" id="slotsList">
            <!-- Slots will be loaded here -->
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-white/10">
            <form onsubmit="addSlot(event)" class="space-y-3">
                <select id="newSlotTime" required
                    class="w-full border-gray-200 dark:border-white/10 rounded-lg py-2 px-3 dark:bg-white/5">
                    <option value="">Select Time</option>
                    <?php for ($h = 10; $h <= 21; $h++): ?>
                        <option value="<?php echo sprintf('%02d:00', $h); ?>">
                            <?php echo date('g:i A', strtotime(sprintf('%02d:00', $h))); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <button type="submit"
                    class="w-full bg-primary text-white font-bold py-2 rounded-lg hover:bg-primary/90 transition-all">
                    Add Slot
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let currentDate = '';

    function openSlotModal(date) {
        currentDate = date;
        document.getElementById('modalDate').textContent = new Date(date + 'T00:00:00').toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('slotModal').classList.remove('hidden');
        document.getElementById('slotModal').classList.add('flex');
        loadSlots(date);
    }

    function closeSlotModal(event) {
        if (!event || event.target.id === 'slotModal') {
            document.getElementById('slotModal').classList.add('hidden');
            document.getElementById('slotModal').classList.remove('flex');
        }
    }

    function loadSlots(date) {
        fetch(`?action=get_slots&date=${date}`)
            .then(r => r.json())
            .then(data => {
                const container = document.getElementById('slotsList');
                if (data.slots.length === 0) {
                    container.innerHTML = '<p class="text-gray-400 text-center py-4">No slots added yet</p>';
                    return;
                }

                container.innerHTML = data.slots.map(slot => `
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-lg mb-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm ${slot.is_booked ? 'text-red-500' : 'text-green-500'}">
                            ${slot.is_booked ? 'event_busy' : 'event_available'}
                        </span>
                        <span class="font-bold text-gray-700 dark:text-gray-300">
                            ${new Date('2000-01-01T' + slot.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}
                        </span>
                    </div>
                    ${!slot.is_booked ? `
                        <button onclick="deleteSlot(${slot.id})" class="text-gray-400 hover:text-red-500">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    ` : '<span class="text-xs text-red-500 font-bold">Booked</span>'}
                </div>
            `).join('');
            });
    }

    function addSlot(event) {
        event.preventDefault();
        const time = document.getElementById('newSlotTime').value;

        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `add_slot=1&date=${currentDate}&start_time=${time}`
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadSlots(currentDate);
                    document.getElementById('newSlotTime').value = '';
                    location.reload(); // Refresh to update calendar
                } else {
                    alert(data.message);
                }
            });
    }

    function deleteSlot(id) {
        if (!confirm('Delete this slot?')) return;

        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `delete_slot=1&slot_id=${id}`
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadSlots(currentDate);
                    location.reload(); // Refresh to update calendar
                }
            });
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>