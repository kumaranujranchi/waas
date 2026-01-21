<?php
/**
 * User Profile Settings
 */

$pageTitle = 'Profile Settings | SiteOnSub';
include __DIR__ . '/../../includes/header.php';

// Require login
requireLogin();

require_once __DIR__ . '/../../models/User.php';

$userId = getCurrentUserId();
$userModel = new User();
$user = $userModel->getUserById($userId);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = sanitizeInput($_POST['full_name'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = [];
    $success = '';

    // Validate Name
    if (empty($fullName)) {
        $errors[] = "Full Name is required.";
    }

    // Password Change Logic
    if (!empty($newPassword)) {
        if (strlen($newPassword) < 8) {
            $errors[] = "New password must be at least 8 characters.";
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }
    }

    if (empty($errors)) {
        // Update Profile Data
        $updateData = [
            'full_name' => $fullName,
            'phone' => $phone
        ];

        // If password is set, update it
        if (!empty($newPassword)) {
            $updateData['password'] = $newPassword; // Model handles hashing
        }

        if ($userModel->updateProfile($userId, $updateData)) {
            setFlashMessage('success', 'Profile updated successfully!');
            // Refresh user data
            $user = $userModel->getUserById($userId); // Reload fresh data
        } else {
            setFlashMessage('error', 'Failed to update profile.');
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}
?>

<main class="flex-1 bg-background-light dark:bg-background-dark py-12 px-6">
    <div class="max-w-[800px] mx-auto">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-2">Profile Settings</h1>
                <p class="text-[#545095] dark:text-gray-400">Update your personal information</p>
            </div>
            <a href="<?php echo baseUrl('dashboard/index.php'); ?>"
                class="px-4 py-2 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white dark:bg-white/5 rounded-xl border border-[#e8e8f3] dark:border-white/10 overflow-hidden">
            <div class="p-8">
                <form method="POST" action="" class="space-y-6">

                    <!-- Profile Picture (Read Only for now or from Google) -->
                    <div class="flex items-center gap-6 pb-6 border-b border-gray-100 dark:border-white/5">
                        <div
                            class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center overflow-hidden border-2 border-white dark:border-white/10 shadow-lg relative">
                            <?php if (!empty($user['avatar'])): ?>
                                <img src="<?php echo e($user['avatar']); ?>" alt="Profile"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-2xl font-bold text-primary">
                                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($user['google_id'])): ?>
                                <div class="absolute bottom-0 right-0 bg-white dark:bg-black rounded-full p-1 shadow-sm"
                                    title="Connected with Google">
                                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg dark:text-white">
                                <?php echo e($user['full_name']); ?>
                            </h3>
                            <p class="text-sm text-gray-500">
                                <?php echo e($user['email']); ?>
                            </p>
                            <?php if (!empty($user['google_id'])): ?>
                                <span
                                    class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Google Account Linked
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Full Name</label>
                            <input type="text" name="full_name" required value="<?php echo e($user['full_name']); ?>"
                                class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email
                                Address</label>
                            <input type="email" value="<?php echo e($user['email']); ?>" disabled
                                class="w-full px-4 py-3 rounded-lg border-slate-200 bg-slate-50 text-slate-500 dark:border-white/10 dark:bg-white/5 cursor-not-allowed" />
                            <p class="text-xs text-slate-400">Email cannot be changed.</p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo e($user['phone'] ?? ''); ?>"
                                class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                placeholder="+1 (555) 000-0000" />
                        </div>
                    </div>

                    <!-- Password Change (Only show if password exists or allow setting one) -->
                    <!-- Password Change (Only show if NOT linked to Google) -->
                    <?php if (empty($user['google_id'])): ?>
                    <div class="pt-6 border-t border-gray-100 dark:border-white/5">
                        <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-4">Change Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">New Password</label>
                                <input type="password" name="new_password" autocomplete="new-password"
                                    class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    placeholder="Leave blank to keep current" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Confirm New Password</label>
                                <input type="password" name="confirm_password" autocomplete="new-password"
                                    class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    placeholder="Confirm new password" />
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="pt-6 border-t border-gray-100 dark:border-white/5">
                        <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/20 rounded-lg p-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                Since you logged in with Google, you don't need a password. Your account is secured by Google.
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="pt-6 border-t border-gray-100 dark:border-white/5 flex justify-end">
                        <button type="submit"
                            class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 shadow-lg shadow-primary/25 transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>