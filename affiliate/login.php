<?php
/**
 * Affiliate Login Page
 * Dedicated login for affiliates
 */

// Include dependencies
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(baseUrl('affiliate/dashboard.php'));
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    require_once __DIR__ . '/../models/User.php';
    $userModel = new User();
    $result = $userModel->login($email, $password);

    if ($result['success']) {
        setFlashMessage('success', 'Welcome to Affiliate Dashboard');
        redirect(baseUrl('affiliate/dashboard.php'));
    } else {
        setFlashMessage('error', $result['message']);
    }
}

$pageTitle = 'Affiliate Login';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="min-h-screen bg-gray-50 dark:bg-[#0f0e1b] py-20">
    <div class="max-w-md mx-auto px-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-2">Affiliate Portal</h1>
            <p class="text-[#545095] dark:text-gray-400">Login to check your earnings</p>
        </div>

        <div class="bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-xl p-8 border border-slate-100 dark:border-white/5">
            <form method="POST" action="" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email Address</label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="you@example.com" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="••••••••" />
                </div>

                <button type="submit"
                    class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:opacity-90 shadow-lg shadow-primary/25 transition-all">
                    Login to Dashboard
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Not an affiliate yet?
                    <a href="<?php echo baseUrl('affiliate-register.php'); ?>"
                        class="font-bold text-primary hover:underline">Join Program</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>