<?php
/**
 * Consultation Booking Page
 */

// Include dependencies first
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Consultation.php';

// Start session if needed (functions.php might do it, but good to be safe for flash messages)
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
        $bookingId = $consultationModel->createBooking($data);

        if ($bookingId) {
            setFlashMessage('success', 'Consultation request submitted successfully! We will contact you soon.');
            redirect(baseUrl('index.php'));
        } else {
            setFlashMessage('error', 'Failed to submit consultation request. Please try again.');
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Handle AJAX Slot Fetching
if (isset($_GET['action']) && $_GET['action'] === 'get_slots' && isset($_GET['date'])) {
    header('Content-Type: application/json');
    $date = $_GET['date'];
    
    // Logic to fetch slots
    $consultationModel = new Consultation();
    $slots = $consultationModel->getAvailableSlots($date);
    
    echo json_encode(['slots' => $slots]);
    exit;
}

// Include header (Output starts here)
include __DIR__ . '/includes/header.php';

// Pre-fill data if user is logged in
$currentUser = getCurrentUser();
$prefillName = $currentUser['full_name'] ?? '';
$prefillEmail = $currentUser['email'] ?? '';
$prefillPhone = $currentUser['phone'] ?? '';
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

        <!-- Right Side: Booking Form -->
        <div
            class="bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-xl shadow-primary/5 p-8 border border-slate-100 dark:border-white/5">
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
                            <option value="">Select type</option>
                            <option value="E-commerce">E-commerce</option>
                            <option value="SaaS Start-up">SaaS Start-up</option>
                            <option value="Real Estate">Real Estate</option>
                            <option value="Professional Services">Professional Services</option>
                            <option value="Agency">Agency</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Project Requirements</label>
                    <textarea name="requirements" rows="3"
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="Tell us about your project goals..."></textarea>
                </div>

                <!-- Availability Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-primary/5 p-4 rounded-xl border border-primary/10">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                           <span class="material-symbols-outlined text-primary text-sm">calendar_month</span>
                           Preferred Date
                       </label>
                        <input type="date" name="preferred_date" id="date-picker" required min="<?php echo date('Y-m-d'); ?>"
                            class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                           <span class="material-symbols-outlined text-primary text-sm">schedule</span>
                           Available Slot
                        </label>
                        <select name="preferred_time" id="time-picker" required disabled
                            class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="">Select Date First</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-4 pt-4">
                    <button type="submit"
                        class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-primary/90 shadow-lg shadow-primary/25 transition-all">
                        Confirm Booking
                    </button>
                    <div class="flex items-center justify-center gap-2 text-slate-400 text-xs">
                        <span class="material-symbols-outlined text-[16px]">lock</span>
                        Secured & Encrypted Data Handling
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const datePicker = document.getElementById('date-picker');
    const timePicker = document.getElementById('time-picker');

    datePicker.addEventListener('change', function() {
        const date = this.value;
        if (!date) return;

        // Reset Time Picker
        timePicker.innerHTML = '<option value="">Loading slots...</option>';
        timePicker.disabled = true;

        // Fetch Slots
        fetch(`consultation.php?action=get_slots&date=${date}`)
            .then(response => response.json())
            .then(data => {
                timePicker.innerHTML = '<option value="">Select Time</option>';
                
                if (data.slots && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.value;
                        option.textContent = slot.label;
                        timePicker.appendChild(option);
                    });
                    timePicker.disabled = false;
                } else {
                    const option = document.createElement('option');
                    option.textContent = "No slots available";
                    timePicker.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error fetching slots:', error);
                timePicker.innerHTML = '<option value="">Error loading slots</option>';
            });
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>