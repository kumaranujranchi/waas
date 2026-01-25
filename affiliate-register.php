<?php
// Include functions and config first (No Output)
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Start session if needed (functions.php usually does this, but safely check)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require Login (Check BEFORE any output)
if (!isset($_SESSION['user_id'])) {
    redirect(baseUrl('auth/login.php?redirect=affiliate-register.php'));
}

// Now include logic
require_once __DIR__ . '/models/Affiliate.php';
$affiliateModel = new Affiliate();
$userId = $_SESSION['user_id'];

// Check if already affiliate
$existing = $affiliateModel->getAffiliateByUserId($userId);
if ($existing) {
    redirect(baseUrl('affiliate/dashboard.php'));
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accept_terms'])) {
        $affiliateModel->createAffiliate($userId);
        setFlashMessage('success', 'Welcome to the Affiliate Program!');
        redirect(baseUrl('affiliate/dashboard.php'));
    } else {
        $error = "You must accept the terms and conditions.";
    }
}

// Include Header (HTML Output starts here)
$pageTitle = 'Become an Affiliate';
require_once __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen bg-gray-50 dark:bg-[#0f0e1b] py-20">
    <div class="container mx-auto px-4">
        <div
            class="max-w-2xl mx-auto bg-white dark:bg-white/5 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-white/10">
            <!-- Header -->
            <div class="bg-primary/10 p-8 text-center">
                <span class="material-symbols-outlined text-6xl text-primary mb-4">handshake</span>
                <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-2">Become an Affiliate Partner</h1>
                <p class="text-gray-600 dark:text-gray-300">Join our program and earn 20% commission on every sale!</p>
            </div>

            <div class="p-8">
                <!-- Benefits -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="flex gap-4">
                        <div
                            class="size-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">payments</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f0e1b] dark:text-white">High Commission</h3>
                            <p class="text-sm text-gray-500">Earn 20% recurring commission on all referrals.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div
                            class="size-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">monitoring</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f0e1b] dark:text-white">Real-time Tracking</h3>
                            <p class="text-sm text-gray-500">Track clicks and earnings in your dashboard.</p>
                        </div>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                    <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
                        <span class="material-symbols-outlined">error</span>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" class="space-y-6">
                    <div
                        class="bg-gray-50 dark:bg-white/5 p-4 rounded-lg border border-gray-200 dark:border-white/10 text-sm text-gray-600 dark:text-gray-300 h-40 overflow-y-auto">
                        <p class="font-bold mb-2">Terms and Conditions:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>You must be 18 years or older to join.</li>
                            <li>Commissions are paid out once the balance reaches $50.</li>
                            <li>Self-referrals are strictly prohibited.</li>
                            <li>We reserve the right to ban accounts for suspicious activity.</li>
                            <li>Commissions are held for 30 days to account for refunds.</li>
                        </ul>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" name="accept_terms" required
                            class="mt-1 w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary transition-all">
                        <span
                            class="text-gray-600 dark:text-gray-300 group-hover:text-[#0f0e1b] dark:group-hover:text-white transition-colors">
                            I have read an agree to the <strong>Affiliate Terms & Conditions</strong>.
                        </span>
                    </label>

                    <button type="submit"
                        class="w-full py-4 bg-primary hover:bg-primary/90 text-white rounded-xl font-bold shadow-lg shadow-primary/25 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                        <span>Activate Affiliate Account</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>