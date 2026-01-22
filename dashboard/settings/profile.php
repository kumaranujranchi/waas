<?php
/**
 * User Profile Settings Page
 */

$pageTitle = 'Edit Profile';
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../../models/User.php';

$userId = getCurrentUserId();
$user = getCurrentUser();

// Handle form submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($fullName)) {
        $error = 'Full name is required';
    } else {
        // Update user profile
        $userModel = new User();
        if ($userModel->updateProfile($userId, ['full_name' => $fullName, 'phone' => $phone])) {
            $success = 'Profile updated successfully!';
            // Refresh user data (session)
            $_SESSION['user_full_name'] = $fullName;
            $user['full_name'] = $fullName;
            $user['phone'] = $phone;
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
    }
}
?>

<!-- Settings Header & Tabs -->
<div class="mb-8">
    <h2 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-6">Settings</h2>

    <div
        class="flex items-center gap-1 bg-gray-100/50 dark:bg-white/5 p-1 rounded-xl w-fit border border-gray-200 dark:border-white/10">
        <a href="profile.php"
            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all bg-white dark:bg-white/10 text-primary shadow-sm">
            Profile Info
        </a>
        <a href="account.php"
            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all text-gray-500 dark:text-gray-400 hover:text-primary">
            Security & Account
        </a>
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (!empty($success)): ?>
    <div
        class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-xl border border-green-200 dark:border-green-900/30 flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
        <span class="material-symbols-outlined">check_circle</span>
        <span class="font-medium">
            <?php echo e($success); ?>
        </span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div
        class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-xl border border-red-200 dark:border-red-900/30 flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
        <span class="material-symbols-outlined">error</span>
        <span class="font-medium">
            <?php echo e($error); ?>
        </span>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Profile Card -->
    <div
        class="bg-white dark:bg-white/5 rounded-2xl p-8 border border-gray-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-purple-600"></div>

        <div class="flex flex-col items-center text-center">
            <div class="relative mb-6">
                <div
                    class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center text-white font-black text-4xl shadow-lg ring-4 ring-white dark:ring-white/5">
                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                </div>
                <div
                    class="absolute -bottom-2 -right-2 w-8 h-8 bg-accent-green rounded-full border-4 border-white dark:border-background-dark flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-white text-xs">verified</span>
                </div>
            </div>

            <h3
                class="text-2xl font-black text-[#0f0e1b] dark:text-white mb-1 group-hover:text-primary transition-colors">
                <?php echo e($user['full_name']); ?>
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 font-medium">
                <?php echo e($user['email']); ?>
            </p>

            <div class="w-full space-y-4 pt-6 border-t border-gray-100 dark:border-white/5 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Member Since</span>
                    <span class="font-bold text-[#0f0e1b] dark:text-white">
                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Account Status</span>
                    <span
                        class="px-3 py-1 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 rounded-full text-xs font-black border border-green-100 dark:border-green-900/30">
                        <?php echo strtoupper($user['status']); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="lg:col-span-2">
        <form method="POST"
            class="bg-white dark:bg-white/5 rounded-2xl p-8 border border-gray-200 dark:border-white/10 shadow-sm">
            <h4 class="text-lg font-black text-[#0f0e1b] dark:text-white mb-8 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">person</span>
                Personal Information
            </h4>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">
                            Full Name
                        </label>
                        <input type="text" name="full_name" value="<?php echo e($user['full_name']); ?>"
                            class="w-full px-4 py-3.5 border border-gray-200 dark:border-white/10 rounded-xl bg-gray-50 dark:bg-white/5 text-[#0f0e1b] dark:text-white font-bold focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                            placeholder="e.g. John Doe" required>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" name="phone" value="<?php echo e($user['phone'] ?? ''); ?>"
                            class="w-full px-4 py-3.5 border border-gray-200 dark:border-white/10 rounded-xl bg-gray-50 dark:bg-white/5 text-[#0f0e1b] dark:text-white font-bold focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                            placeholder="+1 (555) 000-0000">
                    </div>
                </div>

                <!-- Email (Read-only) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">
                            Email Address
                        </label>
                        <span class="flex items-center gap-1 text-[10px] font-black text-gray-400 uppercase">
                            <span class="material-symbols-outlined text-[12px]">lock</span>
                            Read Only
                        </span>
                    </div>
                    <input type="email" value="<?php echo e($user['email']); ?>"
                        class="w-full px-4 py-3.5 border border-gray-200 dark:border-white/10 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-400 dark:text-gray-500 font-bold cursor-not-allowed"
                        disabled>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-100 dark:border-white/5">
                    <button type="submit"
                        class="flex-1 px-8 py-3.5 bg-primary text-white rounded-xl font-black shadow-lg shadow-primary/25 hover:bg-primary-dark hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                        Update Profile
                    </button>
                    <a href="<?php echo baseUrl('dashboard/index.php'); ?>"
                        class="px-8 py-3.5 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 rounded-xl font-black hover:bg-gray-200 dark:hover:bg-white/10 transition-all text-center">
                        Dismiss
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>