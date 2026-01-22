<?php
/**
 * User Account Settings Page
 */

$pageTitle = 'Account Settings';
include __DIR__ . '/../includes/header.php';

$user = getCurrentUser();

// Handle password change
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword)) {
            $error = 'Current password is required';
        } elseif (empty($newPassword)) {
            $error = 'New password is required';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Passwords do not match';
        } elseif (strlen($newPassword) < 8) {
            $error = 'Password must be at least 8 characters';
        } else {
            // Verify current password
            require_once __DIR__ . '/../../models/User.php';
            $userModel = new User();
            if ($userModel->verifyPassword($currentPassword, $user['password'])) {
                // Update password
                if ($userModel->updatePassword($_SESSION['user_id'], $newPassword)) {
                    $success = 'Password changed successfully!';
                } else {
                    $error = 'Failed to update password';
                }
            } else {
                $error = 'Current password is incorrect';
            }
        }
    } elseif ($_POST['action'] === 'delete_account') {
        $confirmDelete = $_POST['confirm_delete'] ?? '';
        if ($confirmDelete !== 'DELETE') {
            $error = 'Please type DELETE to confirm';
        } else {
            // Delete account
            require_once __DIR__ . '/../../models/User.php';
            $userModel = new User();
            if ($userModel->deleteUser($_SESSION['user_id'])) {
                session_destroy();
                redirect(baseUrl('index.php'));
            } else {
                $error = 'Failed to delete account';
            }
        }
    }
}
?>

<!-- Account Settings Header -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-2">Account Settings</h2>
    <p class="text-gray-600 dark:text-gray-400">Manage your account security and preferences</p>
</div>

<!-- Success/Error Messages -->
<?php if (!empty($success)): ?>
    <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-lg border border-green-300 dark:border-green-700 flex items-center gap-3">
        <span class="material-symbols-outlined">check_circle</span>
        <span><?php echo e($success); ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-lg border border-red-300 dark:border-red-700 flex items-center gap-3">
        <span class="material-symbols-outlined">error</span>
        <span><?php echo e($error); ?></span>
    </div>
<?php endif; ?>

<div class="space-y-8">
    <!-- Change Password Section -->
    <?php if (empty($user['google_id'])): ?>
        <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10">
            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-6">Change Password</h3>
            
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="change_password">
                
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">
                        Current Password
                    </label>
                    <input 
                        type="password" 
                        name="current_password"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-white/10 rounded-lg bg-white dark:bg-white/5 text-[#0f0e1b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">
                        New Password
                    </label>
                    <input 
                        type="password" 
                        name="new_password"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-white/10 rounded-lg bg-white dark:bg-white/5 text-[#0f0e1b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    >
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Minimum 8 characters</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">
                        Confirm New Password
                    </label>
                    <input 
                        type="password" 
                        name="confirm_password"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-white/10 rounded-lg bg-white dark:bg-white/5 text-[#0f0e1b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    >
                </div>

                <button 
                    type="submit"
                    class="px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary-dark transition-all"
                >
                    Update Password
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 border border-blue-200 dark:border-blue-800">
            <div class="flex gap-4">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-2xl flex-shrink-0">info</span>
                <div>
                    <h3 class="font-bold text-blue-900 dark:text-blue-200 mb-1">Google Account Linked</h3>
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        Your account is secured by Google. You don't need to manage a password. To change your password, please visit your Google Account settings.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Connected Accounts -->
    <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10">
        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-6">Connected Accounts</h3>
        
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-white/10 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-red-600 dark:text-red-400">G</span>
                    </div>
                    <div>
                        <p class="font-medium text-[#0f0e1b] dark:text-white">Google Account</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <?php echo !empty($user['google_id']) ? 'Connected' : 'Not connected'; ?>
                        </p>
                    </div>
                </div>
                <?php if (!empty($user['google_id'])): ?>
                    <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full text-xs font-bold">
                        Connected
                    </span>
                <?php else: ?>
                    <button class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition-all text-sm">
                        Connect
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-red-300 dark:border-red-900/30">
        <h3 class="text-xl font-bold text-red-600 dark:text-red-400 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">warning</span>
            Danger Zone
        </h3>
        
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Permanently delete your account and all associated data. This action cannot be undone.
        </p>

        <button 
            onclick="document.getElementById('deleteModal').classList.remove('hidden')"
            class="px-6 py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition-all"
        >
            Delete Account
        </button>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-900 rounded-lg max-w-md w-full">
        <div class="p-6 border-b border-gray-200 dark:border-white/10">
            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-red-600">error</span>
                Delete Account
            </h3>
        </div>

        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" value="delete_account">
            
            <p class="text-gray-600 dark:text-gray-400">
                This action is permanent and cannot be undone. All your data will be deleted.
            </p>

            <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                Type <span class="font-mono bg-gray-100 dark:bg-white/10 px-2 py-1 rounded">DELETE</span> to confirm:
            </p>

            <input 
                type="text" 
                name="confirm_delete"
                placeholder="Type DELETE"
                class="w-full px-4 py-3 border border-gray-300 dark:border-white/10 rounded-lg bg-white dark:bg-white/5 text-[#0f0e1b] dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500"
            >

            <div class="flex gap-3">
                <button 
                    type="button"
                    onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-white/20 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition-all"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition-all"
                >
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Close modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
