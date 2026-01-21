<?php
/**
 * User Registration Page
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
if (isLoggedIn()) {
    redirect(baseUrl('dashboard/index.php'));
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = sanitizeInput($_POST['full_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = [];

    if (empty($fullName))
        $errors[] = 'Full name is required';
    if (empty($email))
        $errors[] = 'Email is required';
    if (!isValidEmail($email))
        $errors[] = 'Invalid email format';
    if (empty($password))
        $errors[] = 'Password is required';
    if (strlen($password) < PASSWORD_MIN_LENGTH)
        $errors[] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters';
    if ($password !== $confirmPassword)
        $errors[] = 'Passwords do not match';

    if (empty($errors)) {
        $userModel = new User();
        $result = $userModel->register([
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => 'customer'
        ]);

        if ($result['success']) {
            // Auto-login after registration
            $userModel->login($email, $password);
            setFlashMessage('success', 'Account created successfully!');
            redirect(baseUrl('dashboard/index.php'));
        } else {
            setFlashMessage('error', $result['message']);
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Include header after logic
$pageTitle = 'Sign Up | SiteOnSub';
include __DIR__ . '/../includes/header.php';
?>

<main class="flex-1 flex items-center justify-center py-12 px-6">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h1 class="text-4xl font-black text-[#0f0e1b] dark:text-white mb-2">Create Account</h1>
            <p class="text-[#545095] dark:text-gray-400">Join thousands of businesses using WaaS</p>
        </div>

        <div class="bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-xl p-8 border border-slate-100 dark:border-white/5">
            <!-- Google Signup -->
            <a href="<?php echo baseUrl('auth/google_login.php'); ?>"
                class="flex items-center justify-center w-full py-4 mb-6 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl hover:bg-slate-50 dark:hover:bg-white/10 transition-all gap-3 group">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6" alt="Google">
                <span class="font-bold text-slate-700 dark:text-white">Sign up with Google</span>
            </a>

            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white dark:bg-[#1c1b2e] text-slate-500">Or sign up with email</span>
                </div>
            </div>

            <form method="POST" action="" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Full Name</label>
                    <input type="text" name="full_name" required value="<?php echo e($_POST['full_name'] ?? ''); ?>"
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="John Doe" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email Address</label>
                    <input type="email" name="email" required value="<?php echo e($_POST['email'] ?? ''); ?>"
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="you@example.com" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Phone (Optional)</label>
                    <input type="tel" name="phone" value="<?php echo e($_POST['phone'] ?? ''); ?>"
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="+1 (555) 000-0000" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="••••••••" />
                    <p class="text-xs text-slate-500">Minimum
                        <?php echo PASSWORD_MIN_LENGTH; ?> characters
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Confirm Password</label>
                    <input type="password" name="confirm_password" required
                        class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="••••••••" />
                </div>

                <div class="flex items-start gap-2">
                    <input type="checkbox" required
                        class="mt-1 rounded border-gray-300 text-primary focus:ring-primary" />
                    <span class="text-sm text-slate-600 dark:text-slate-400">
                        I agree to the <a href="<?php echo baseUrl('terms.php'); ?>"
                            class="text-primary hover:underline">Terms of Service</a> and
                        <a href="<?php echo baseUrl('privacy-policy.php'); ?>"
                            class="text-primary hover:underline">Privacy Policy</a>
                    </span>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:opacity-90 shadow-lg shadow-primary/25 transition-all">
                    Create Account
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Already have an account?
                    <a href="<?php echo baseUrl('auth/login.php'); ?>"
                        class="font-bold text-primary hover:underline">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>