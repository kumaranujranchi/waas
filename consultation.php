<?php
/**
 * Consultation Booking Page
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consultationModel = new Consultation();

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

    if (empty($errors)) {
        // Check if slot is selected and still available
        $slotId = $_POST['slot_id'] ?? null;
        if ($slotId && $consultationModel->isSlotAvailable($slotId)) {
            $bookingId = $consultationModel->createBooking($data);
            
            if ($bookingId) {
                // Mark slot as booked
                $consultationModel->bookSlot($slotId, $bookingId);
                setFlashMessage('success', 'Consultation request submitted successfully! We will contact you soon.');
                redirect(baseUrl('index.php'));
            } else {
                setFlashMessage('error', 'Failed to submit consultation request. Please try again.');
            }
        } else {
            setFlashMessage('error', 'This slot is no longer available. Please select another time.');
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Handle AJAX Slot Fetching
if (isset($_GET['action']) && $_GET['action'] === 'get_slots' && isset($_GET['date'])) {
    header('Content-Type: application/json');
    $date = $_GET['date'];
    
    $consultationModel = new Consultation();
    $slots = $consultationModel->getAvailableSlots($date);
    
    // Format slots for frontend
    $formattedSlots = [];
    foreach ($slots as $slot) {
        $formattedSlots[] = [
            'id' => $slot['id'],
            'value' => $slot['start_time'],
            'label' => date('g:i A', strtotime($slot['start_time'])) . ' - ' . date('g:i A', strtotime($slot['end_time']))
        ];
    }
    
    echo json_encode(['slots' => $formattedSlots]);
    exit;
}

// Handle AJAX Available Dates Fetching
if (isset($_GET['action']) && $_GET['action'] === 'get_dates') {
    header('Content-Type: application/json');
    $consultationModel = new Consultation();
    $dates = $consultationModel->getAvailableDates();
    echo json_encode(['dates' => $dates]);
    exit;
}

// Include header (Output starts here)
include __DIR__ . '/includes/header.php';

// Pre-fill data if user is logged in
$currentUser = getCurrentUser();
$prefillName = $currentUser['full_name'] ?? '';
$prefillEmail = $currentUser['email'] ?? '';
$prefillPhone = $currentUser['phone'] ?? '';

// Get current month/year for calendar
$currentMonth = date('n');
$currentYear = date('Y');
$firstDay = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
$daysInMonth = date('t', $firstDay);
$dayOfWeek = date('w', $firstDay);
$monthName = date('F Y', $firstDay);

// Get available dates
$consultationModel = new Consultation();
$availableDates = $consultationModel->getAvailableDates();
?>

<main class="flex-1 flex items-center justify-center py-12 px-6 md:px-20">
    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        <!-- Left Side: Value Proposition -->
        <div class="flex flex-col space-y-8">
            <div>
                <span class="text-primary font-semibold text-sm tracking-widest uppercase">Expert Consultations</span>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mt-4 text-[#0f0e1b] dark:text-white">
                    Transform Your Digital Presence with a Strategy Call
                </h1>
                <p class="text-lg text-slate-600 dark:text-slate-400 mt-6 leading-relaxed">
                    Discuss your project with our WaaS experts and find the perfect software solution for your business
                    growth. We help you skip the technical hurdles and focus on scaling.
                </p>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="bg-primary/10 p-2 rounded-lg text-primary">
                        <span class="material-symbols-outlined">map</span>
                    </div>
                    <div>
                        <h4 class="font-bold">Custom solution mapping</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Tailored strategies to fit your unique
                            business model and goals.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="bg-primary/10 p-2 rounded-lg text-primary">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div>
                        <h4 class="font-bold">Transparent pricing & subscription</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">No hidden fees. Predictable monthly costs
                            for high-end software.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="bg-primary/10 p-2 rounded-lg text-primary">
                        <span class="material-symbols-outlined">trending_up</span>
                    </div>
                    <div>
                        <h4 class="font-bold">Scalability consultation</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Infrastructure advice that grows as fast
                            as your user base does.</p>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 dark:border-white/10">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <span class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full border-2 border-background-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                        <div class="w-8 h-8 rounded-full border-2 border-background-light"
                            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></div>
                        <div class="w-8 h-8 rounded-full border-2 border-background-light"
                            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
                    </span>
                    <span class="ml-2">Trusted by 500+ businesses worldwide</span>
                </p>
            </div>
        </div>

        <!-- Right Side: Booking Form with Calendly-Style Date/Time Picker -->
        <div class="bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-xl shadow-primary/5 p-8 border border-slate-100 dark:border-white/5">
            <div class="flex items-center justify-between mb-8">
                <div class="flex gap-2">
                    <div class="h-1.5 w-8 rounded-full bg-primary"></div>
                    <div class="h-1.5 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                    <div class="h-1.5 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Step 1 of 3</span>
            </div>

            <form method="POST" action="" class="space-y-6">
                <!-- Contact Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Full Name</label>
                        <input type="text" name="full_name" required value="<?php echo e($prefillName); ?>"
                            class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                            placeholder="John Doe" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Work Email</label>
                        <input type="email" name="email" required value="<?php echo e($prefillEmail); ?>"
                            class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                            placeholder="john@company.com" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Phone Number</label>
                        <input type="tel" name="phone" required value="<?php echo e($prefillPhone); ?>"
                            class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                            placeholder="+1 (555) 000-0000" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Business Type</label>
                        <select name="business_type" required
                            class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                            <option value="">Select your industry</option>
                            <option value="E-commerce">E-commerce</option>
                            <option value="SaaS">SaaS</option>
                            <option value="Agency">Agency</option>
                            <option value="Startup">Startup</option>
                            <option value="Enterprise">Enterprise</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Project Requirements
                        <span class="text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <textarea name="requirements" rows="4"
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none"
                        placeholder="Tell us about your project, goals, and any specific requirements..."></textarea>
                </div>

                <!-- Calendly-Style Date & Time Picker -->
                <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-white/10">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Select a Date & Time</h3>
                    
                    <!-- Calendar -->
                    <div class="border border-slate-200 dark:border-white/10 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <button type="button" onclick="changeMonth(-1)" class="p-1 hover:bg-gray-100 dark:hover:bg-white/5 rounded">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </button>
                            <span class="text-sm font-bold" id="monthDisplay"><?php echo $monthName; ?></span>
                            <button type="button" onclick="changeMonth(1)" class="p-1 hover:bg-gray-100 dark:hover:bg-white/5 rounded">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-7 gap-1">
                            <?php foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day): ?>
                            <div class="text-center text-xs font-bold text-gray-500 py-1"><?php echo $day; ?></div>
                            <?php endforeach; ?>

                            <?php
                            for ($i = 0; $i < $dayOfWeek; $i++) {
                                echo '<div class="aspect-square"></div>';
                            }

                            for ($day = 1; $day <= $daysInMonth; $day++) {
                                $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                                $isToday = $date === date('Y-m-d');
                                $isPast = strtotime($date) < strtotime('today');
                                $hasSlots = in_array($date, $availableDates);
                            ?>
                            <button type="button"
                                onclick="<?php echo (!$isPast && $hasSlots) ? "selectDate('$date')" : ''; ?>"
                                class="aspect-square rounded text-xs font-bold transition-all relative
                                    <?php echo $isPast ? 'text-gray-300 dark:text-gray-700 cursor-not-allowed' : ''; ?>
                                    <?php echo (!$isPast && $hasSlots) ? 'hover:bg-primary/10 hover:text-primary cursor-pointer' : ''; ?>
                                    <?php echo (!$isPast && !$hasSlots) ? 'text-gray-400 cursor-not-allowed' : ''; ?>
                                    <?php echo $isToday ? 'border border-primary' : ''; ?>"
                                <?php echo ($isPast || !$hasSlots) ? 'disabled' : ''; ?>>
                                <?php echo $day; ?>
                                <?php if ($hasSlots && !$isPast): ?>
                                <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>
                                <?php endif; ?>
                            </button>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Time Slots -->
                    <div id="timeSlotsContainer" class="hidden">
                        <input type="hidden" name="preferred_date" id="selectedDate">
                        <input type="hidden" name="preferred_time" id="selectedTime">
                        <input type="hidden" name="slot_id" id="selectedSlotId">
                        
                        <p class="text-xs text-gray-500 mb-2" id="selectedDateDisplay"></p>
                        <div id="timeSlots" class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto">
                            <!-- Slots loaded via JS -->
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-primary text-white font-bold py-4 rounded-lg hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">send</span>
                    Confirm Booking
                </button>

                <p class="text-xs text-center text-slate-400 flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-sm">lock</span>
                    Secured & Encrypted Data Handling
                </p>
            </form>
        </div>
    </div>
</main>

<script>
let currentMonth = <?php echo $currentMonth; ?>;
let currentYear = <?php echo $currentYear; ?>;
let availableDates = <?php echo json_encode($availableDates); ?>;

function changeMonth(delta) {
    // Month navigation can be implemented if needed
    alert('Month navigation - to be implemented');
}

function selectDate(date) {
    document.getElementById('selectedDate').value = date;
    document.getElementById('selectedDateDisplay').textContent = new Date(date + 'T00:00:00').toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric'
    });

    // Load time slots
    fetch(`?action=get_slots&date=${date}`)
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('timeSlots');
            
            if (data.slots.length === 0) {
                container.innerHTML = '<p class="col-span-2 text-gray-400 text-center py-4 text-sm">No available slots</p>';
            } else {
                container.innerHTML = data.slots.map(slot => `
                    <button type="button" onclick="selectSlot(${slot.id}, '${slot.value}', '${slot.label}')"
                        class="px-3 py-2 border-2 border-slate-200 dark:border-white/10 rounded-lg hover:border-primary hover:bg-primary/5 transition-all text-sm font-medium slot-btn">
                        ${slot.label}
                    </button>
                `).join('');
            }

            document.getElementById('timeSlotsContainer').classList.remove('hidden');
        });
}

function selectSlot(slotId, time, label) {
    document.getElementById('selectedSlotId').value = slotId;
    document.getElementById('selectedTime').value = time;
    
    // Highlight selected slot
    document.querySelectorAll('.slot-btn').forEach(btn => {
        btn.classList.remove('border-primary', 'bg-primary/10');
        btn.classList.add('border-slate-200', 'dark:border-white/10');
    });
    event.target.classList.add('border-primary', 'bg-primary/10');
    event.target.classList.remove('border-slate-200', 'dark:border-white/10');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>