<?php
/**
 * Consultation Booking Page - Calendly Style
 */

// Include dependencies first
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Consultation.php';

// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = 'Book a Consultation | SiteOnSub';
$consultationModel = new Consultation();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_consultation'])) {
    $data = [
        'user_id' => getCurrentUserId(),
        'full_name' => sanitizeInput($_POST['full_name'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'phone' => sanitizeInput($_POST['phone'] ?? ''),
        'business_type' => sanitizeInput($_POST['business_type'] ?? ''),
        'requirements' => sanitizeInput($_POST['requirements'] ?? ''),
        'preferred_date' => $_POST['preferred_date'] ?? null,
        'preferred_time' => $_POST['preferred_time'] ?? null,
        'status' => 'pending'
    ];

    $errors = [];

    if (empty($data['full_name']))
        $errors[] = 'Full name is required';
    if (empty($data['email']) || !isValidEmail($data['email']))
        $errors[] = 'Valid email is required';
    if (empty($data['phone']))
        $errors[] = 'Phone number is required';
    if (empty($data['business_type']))
        $errors[] = 'Business type is required';
    if (empty($data['preferred_date']) || empty($data['preferred_time']))
        $errors[] = 'Please select a date and time';

    if (empty($errors)) {
        // Check if slot is still available
        $slotId = $_POST['slot_id'] ?? null;
        if ($slotId && $consultationModel->isSlotAvailable($slotId)) {
            $bookingId = $consultationModel->createBooking($data);
            
            if ($bookingId) {
                // Mark slot as booked
                $consultationModel->bookSlot($slotId, $bookingId);
                setFlashMessage('success', 'Consultation booked successfully! We will contact you soon.');
                redirect(baseUrl('index.php'));
            } else {
                setFlashMessage('error', 'Failed to book consultation. Please try again.');
            }
        } else {
            setFlashMessage('error', 'This slot is no longer available. Please select another time.');
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Get available dates for AJAX
if (isset($_GET['action']) && $_GET['action'] === 'get_dates') {
    header('Content-Type: application/json');
    $dates = $consultationModel->getAvailableDates();
    echo json_encode(['dates' => $dates]);
    exit;
}

// Get slots for a date (AJAX)
if (isset($_GET['action']) && $_GET['action'] === 'get_slots' && isset($_GET['date'])) {
    header('Content-Type: application/json');
    $date = $_GET['date'];
    $slots = $consultationModel->getAvailableSlots($date);
    
    $formattedSlots = [];
    foreach ($slots as $slot) {
        $formattedSlots[] = [
            'id' => $slot['id'],
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
            'label' => date('g:i A', strtotime($slot['start_time'])) . ' - ' . date('g:i A', strtotime($slot['end_time']))
        ];
    }
    
    echo json_encode(['slots' => $formattedSlots]);
    exit;
}

// Get current month/year for calendar
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

$firstDay = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
$daysInMonth = date('t', $firstDay);
$dayOfWeek = date('w', $firstDay);
$monthName = date('F Y', $firstDay);

// Get available dates for this month
$availableDates = $consultationModel->getAvailableDates();

// Pre-fill data if user is logged in
$currentUser = getCurrentUser();
$prefillName = $currentUser['full_name'] ?? '';
$prefillEmail = $currentUser['email'] ?? '';
$prefillPhone = $currentUser['phone'] ?? '';

// Include header
include __DIR__ . '/includes/header.php';
?>

<main class="flex-1 py-12 px-6 md:px-20">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="text-primary font-semibold text-sm tracking-widest uppercase">Book a Consultation</span>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mt-4 text-[#0f0e1b] dark:text-white">
                Select a Date & Time
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 mt-4">
                Choose a convenient time for your free 1-hour consultation
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Calendar Section -->
            <div class="bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-xl p-6 border border-slate-100 dark:border-white/5">
                <!-- Month Navigation -->
                <div class="flex items-center justify-between mb-6">
                    <a href="?month=<?php echo $currentMonth == 1 ? 12 : $currentMonth - 1; ?>&year=<?php echo $currentMonth == 1 ? $currentYear - 1 : $currentYear; ?>"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                    <span class="text-lg font-bold text-[#0f0e1b] dark:text-white">
                        <?php echo $monthName; ?>
                    </span>
                    <a href="?month=<?php echo $currentMonth == 12 ? 1 : $currentMonth + 1; ?>&year=<?php echo $currentMonth == 12 ? $currentYear + 1 : $currentYear; ?>"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-2">
                    <?php foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day): ?>
                    <div class="text-center text-xs font-bold text-gray-500 py-2">
                        <?php echo $day; ?>
                    </div>
                    <?php endforeach; ?>

                    <?php
                    // Empty cells before first day
                    for ($i = 0; $i < $dayOfWeek; $i++) {
                        echo '<div class="aspect-square"></div>';
                    }

                    // Days of month
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                        $isToday = $date === date('Y-m-d');
                        $isPast = strtotime($date) < strtotime('today');
                        $hasSlots = in_array($date, $availableDates);
                    ?>
                    <button type="button"
                        onclick="<?php echo (!$isPast && $hasSlots) ? "selectDate('$date')" : ''; ?>"
                        class="aspect-square rounded-lg flex items-center justify-center text-sm font-bold transition-all
                            <?php echo $isPast ? 'text-gray-300 dark:text-gray-700 cursor-not-allowed' : ''; ?>
                            <?php echo (!$isPast && $hasSlots) ? 'hover:bg-primary/10 hover:text-primary cursor-pointer' : ''; ?>
                            <?php echo (!$isPast && !$hasSlots) ? 'text-gray-400 cursor-not-allowed' : ''; ?>
                            <?php echo $isToday ? 'border-2 border-primary' : ''; ?>"
                        <?php echo ($isPast || !$hasSlots) ? 'disabled' : ''; ?>>
                        <?php echo $day; ?>
                        <?php if ($hasSlots && !$isPast): ?>
                        <span class="absolute w-1.5 h-1.5 bg-primary rounded-full mt-6"></span>
                        <?php endif; ?>
                    </button>
                    <?php } ?>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-white/10">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <div class="w-3 h-3 bg-primary rounded-full"></div>
                        <span>Available dates</span>
                    </div>
                </div>
            </div>

            <!-- Booking Form Section -->
            <div id="bookingForm" class="bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-xl p-6 border border-slate-100 dark:border-white/5">
                <div id="selectDatePrompt" class="flex flex-col items-center justify-center h-full text-center py-12">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-700 mb-4">event</span>
                    <p class="text-gray-500 dark:text-gray-400">Select a date from the calendar to see available time slots</p>
                </div>

                <form id="consultationForm" method="POST" class="hidden space-y-6">
                    <input type="hidden" name="book_consultation" value="1">
                    <input type="hidden" name="slot_id" id="selectedSlotId">
                    <input type="hidden" name="preferred_date" id="selectedDate">
                    <input type="hidden" name="preferred_time" id="selectedTime">

                    <div>
                        <h3 class="font-bold text-lg mb-2" id="selectedDateDisplay"></h3>
                        <p class="text-sm text-gray-500">Select a time slot</p>
                    </div>

                    <div id="timeSlots" class="space-y-2 max-h-48 overflow-y-auto">
                        <!-- Time slots will be loaded here -->
                    </div>

                    <div class="space-y-4 pt-4 border-t border-gray-200 dark:border-white/10">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Full Name</label>
                                <input type="text" name="full_name" required value="<?php echo e($prefillName); ?>"
                                    class="w-full px-4 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email</label>
                                <input type="email" name="email" required value="<?php echo e($prefillEmail); ?>"
                                    class="w-full px-4 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Phone</label>
                                <input type="tel" name="phone" required value="<?php echo e($prefillPhone); ?>"
                                    class="w-full px-4 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Business Type</label>
                                <select name="business_type" required
                                    class="w-full px-4 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                                    <option value="">Select</option>
                                    <option value="E-commerce">E-commerce</option>
                                    <option value="SaaS">SaaS</option>
                                    <option value="Agency">Agency</option>
                                    <option value="Startup">Startup</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Requirements (Optional)</label>
                            <textarea name="requirements" rows="3"
                                class="w-full px-4 py-2 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm"
                                placeholder="Tell us about your project..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90 transition-all">
                            Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function selectDate(date) {
    document.getElementById('selectedDate').value = date;
    document.getElementById('selectedDateDisplay').textContent = new Date(date + 'T00:00:00').toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    // Load time slots
    fetch(`?action=get_slots&date=${date}`)
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('timeSlots');
            
            if (data.slots.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center py-4">No available slots for this date</p>';
                return;
            }

            container.innerHTML = data.slots.map(slot => `
                <button type="button" onclick="selectSlot(${slot.id}, '${slot.start_time}', '${slot.label}')"
                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-white/10 rounded-lg hover:border-primary hover:bg-primary/5 transition-all text-left font-medium slot-btn">
                    ${slot.label}
                </button>
            `).join('');

            document.getElementById('selectDatePrompt').classList.add('hidden');
            document.getElementById('consultationForm').classList.remove('hidden');
        });
}

function selectSlot(slotId, time, label) {
    document.getElementById('selectedSlotId').value = slotId;
    document.getElementById('selectedTime').value = time;
    
    // Highlight selected slot
    document.querySelectorAll('.slot-btn').forEach(btn => {
        btn.classList.remove('border-primary', 'bg-primary/10');
        btn.classList.add('border-gray-200', 'dark:border-white/10');
    });
    event.target.classList.add('border-primary', 'bg-primary/10');
    event.target.classList.remove('border-gray-200', 'dark:border-white/10');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>