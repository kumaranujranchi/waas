<?php
/**
 * User Login Page
 */

// Include dependencies first
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include models
require_once __DIR__ . '/../models/User.php';

// Redirect if already logged in
// Redirect if already logged in
if (isLoggedIn()) {
    $redirectUrl = $_GET['redirect'] ?? null;
    if ($redirectUrl) {
        redirect(baseUrl(urldecode($redirectUrl)));
    }

    if (isAdmin()) {
        redirect(baseUrl('admin/index.php'));
    } else {
        redirect(baseUrl('dashboard/index.php'));
    }
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        setFlashMessage('error', 'Please fill in all fields');
    } else {
        $userModel = new User();
        $result = $userModel->login($email, $password);

        if ($result['success']) {
            setFlashMessage('success', 'Welcome back!');

            // Check for redirect param
            $redirectUrl = $_REQUEST['redirect'] ?? null;
            if (!empty($redirectUrl)) {
                // Decode just in case
                $redirectUrl = urldecode($redirectUrl);
                // Ensure it's a valid internal URL to prevent open redirect
                // For now, valid if it's a relative path or starts with SITE_URL
                redirect(baseUrl($redirectUrl));
            }

            if (isAdmin()) {
                redirect(baseUrl('admin/index.php'));
            } else {
                redirect(baseUrl('dashboard/index.php'));
            }
        } else {
            setFlashMessage('error', $result['message']);
        }
    }
}

// Include header after logic
$pageTitle = 'Login | SiteOnSub';
include __DIR__ . '/../includes/header.php';
?>

<main class="flex-1 flex items-center justify-center py-12 px-6">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h1 class="text-4xl font-black text-[#0f0e1b] dark:text-white mb-2">Welcome Back</h1>
            <p class="text-[#545095] dark:text-gray-400">Sign in to access your dashboard</p>
        </div>

        <div class="bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-xl p-8 border border-slate-100 dark:border-white/5">
            <!-- Google Login -->
            <a href="<?php echo baseUrl('auth/google_login.php' . ($redirectParam ? '?redirect=' . urlencode($redirectParam) : '')); ?>"
                class="flex items-center justify-center w-full py-4 mb-6 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl hover:bg-slate-50 dark:hover:bg-white/10 transition-all gap-3 group">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6" alt="Google">
                <span class="font-bold text-slate-700 dark:text-white">Continue with Google</span>
            </a>

            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white dark:bg-[#1c1b2e] text-slate-500">Or sign in with email</span>
                </div>
            </div>

            <form method="POST" action="" class="space-y-6">
                <!-- Preserve Redirect URL -->
                <!-- Preserve Redirect URL -->
                <?php
                $redirectParam = $_REQUEST['redirect'] ?? null;
                if ($redirectParam):
                    ?>
                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectParam); ?>">
                <?php endif; ?>

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

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary" />
                        <span class="text-sm text-slate-600 dark:text-slate-400">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-medium text-primary hover:underline">Forgot password?</a>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:opacity-90 shadow-lg shadow-primary/25 transition-all">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Don't have an account?
                    <a href="<?php echo baseUrl('auth/register.php' . ($redirectParam ? '?redirect=' . urlencode($redirectParam) : '')); ?>"
                        class="font-bold text-primary hover:underline">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>