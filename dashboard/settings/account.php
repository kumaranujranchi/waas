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
                exit;
            } else {
                $error = 'Failed to delete account';
            }
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
            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all text-gray-500 dark:text-gray-400 hover:text-primary">
            Profile Info
        </a>
        <a href="account.php"
            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all bg-white dark:bg-white/10 text-primary shadow-sm">
            Security & Account
        </a>
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (!empty($success)): ?>
    <div
        class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-xl border border-green-200 dark:border-green-900/30 flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
        <span class="material-symbols-outlined">check_circle</span>
        <span class="font-medium"><?php echo e($success); ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div
        class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-xl border border-red-200 dark:border-red-900/30 flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
        <span class="material-symbols-outlined">error</span>
        <span class="font-medium"><?php echo e($error); ?></span>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 gap-8">
    <!-- Security Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <h3 class="text-xl font-black text-[#0f0e1b] dark:text-white mb-2">Password & Security</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your login credentials and keep your account
                secure.</p>
        </div>

        <div class="lg:col-span-2">
            <?php if (empty($user['google_id'])): ?>
                <div
                    class="bg-white dark:bg-white/5 rounded-2xl p-8 border border-gray-200 dark:border-white/10 shadow-sm transition-all hover:shadow-md">
                    <form method="POST" class="space-y-6">
                        <input type="hidden" name="action" value="change_password">

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">
                                    Current Password
                                </label>
                                <input type="password" name="current_password"
                                    class="w-full px-4 py-3.5 border border-gray-200 dark:border-white/10 rounded-xl bg-gray-50 dark:bg-white/5 text-[#0f0e1b] dark:text-white font-bold focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                                    required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">
                                        New Password
                                    </label>
                                    <input type="password" name="new_password"
                                        class="w-full px-4 py-3.5 border border-gray-200 dark:border-white/10 rounded-xl bg-gray-50 dark:bg-white/5 text-[#0f0e1b] dark:text-white font-bold focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                                        required>
                                    <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-tight">Minimum 8
                                        characters required</p>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">
                                        Confirm New Password
                                    </label>
                                    <input type="password" name="confirm_password"
                                        class="w-full px-4 py-3.5 border border-gray-200 dark:border-white/10 rounded-xl bg-gray-50 dark:bg-white/5 text-[#0f0e1b] dark:text-white font-bold focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                                        required>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="px-8 py-3.5 bg-primary text-white rounded-xl font-black shadow-lg shadow-primary/25 hover:bg-primary-dark hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">vpn_key</span>
                            Update Password
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-8 border border-blue-200 dark:border-blue-900/30 flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0 shadow-sm border border-blue-200 dark:border-blue-800">
                        <span class="material-symbols-outlined">google</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-blue-900 dark:text-blue-200 mb-1">Google Account Linked</h3>
                        <p class="text-sm text-blue-700 dark:text-blue-300 font-medium leading-relaxed">
                            Your account is secured by Google. You don't need to manage a password here. To change your
                            security settings, please visit your <a href="https://myaccount.google.com/security"
                                target="_blank"
                                class="font-bold underline hover:text-blue-900 dark:hover:text-blue-100 transition-colors">Google
                                Account Security</a> page.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Connected Accounts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 border-t border-gray-100 dark:border-white/5 pt-8">
        <div class="lg:col-span-1">
            <h3 class="text-xl font-black text-[#0f0e1b] dark:text-white mb-2">Connected Accounts</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage third-party connections and authentication
                methods.</p>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-white/5 rounded-2xl p-6 border border-gray-200 dark:border-white/10 shadow-sm">
                <div
                    class="flex items-center justify-between p-6 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-white dark:bg-white/10 rounded-xl flex items-center justify-center shadow-sm">
                            <img src="https://www.google.com/favicon.ico" class="w-6 h-6" alt="Google">
                        </div>
                        <div>
                            <p class="font-black text-[#0f0e1b] dark:text-white">Google Authentication</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 mt-1">
                                <?php echo !empty($user['google_id']) ? '<span class="text-accent-green">• Connected</span>' : '<span class="text-gray-400">• Disconnected</span>'; ?>
                            </p>
                        </div>
                    </div>
                    <?php if (!empty($user['google_id'])): ?>
                        <span
                            class="px-4 py-1.5 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-100 dark:border-green-900/30">
                            Active
                        </span>
                    <?php else: ?>
                        <button
                            class="px-6 py-2 bg-primary text-white rounded-lg font-black hover:bg-primary-dark transition-all text-xs uppercase tracking-widest">
                            Connect
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 border-t border-gray-100 dark:border-white/5 pt-8">
        <div class="lg:col-span-1">
            <h3 class="text-xl font-black text-red-600 dark:text-red-400 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Irreversible actions concerning your account data and
                privacy.</p>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-red-50/50 dark:bg-red-900/10 rounded-2xl p-8 border border-red-100 dark:border-red-900/20">
                <h4 class="text-lg font-black text-red-900 dark:text-red-200 mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-600">delete_forever</span>
                    Delete Account
                </h4>
                <p class="text-sm text-red-700 dark:text-red-300 font-medium mb-6">
                    Once you delete your account, there is no going back. Please be certain. All your orders,
                    subscriptions, and profile data will be permanently removed.
                </p>

                <button onclick="document.getElementById('deleteModal').classList.remove('hidden')"
                    class="px-8 py-3.5 bg-red-600 text-white rounded-xl font-black shadow-lg shadow-red-600/25 hover:bg-red-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">person_remove</span>
                    Delete Forever
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal"
    class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-in fade-in zoom-in-95 duration-200">
    <div
        class="bg-white dark:bg-background-dark rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-gray-200 dark:border-white/10">
        <div class="p-8 border-b border-gray-100 dark:border-white/5 bg-red-50 dark:bg-red-900/10">
            <div
                class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center text-red-600 mb-4 shadow-sm border border-red-200 dark:border-red-800">
                <span class="material-symbols-outlined text-3xl">warning</span>
            </div>
            <h3 class="text-2xl font-black text-red-900 dark:text-red-200">
                Are you sure?
            </h3>
        </div>

        <form method="POST" class="p-8 space-y-6">
            <input type="hidden" name="action" value="delete_account">

            <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed">
                This will permanently delete your account and remove all data from our servers.
            </p>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3">
                    Type <span class="text-red-600 font-mono">DELETE</span> to confirm
                </label>
                <input type="text" name="confirm_delete" placeholder="DELETE"
                    class="w-full px-4 py-4 border-2 border-red-100 dark:border-red-900/30 rounded-xl bg-gray-50 dark:bg-white/5 text-[#0f0e1b] dark:text-white font-black text-center focus:outline-none focus:ring-4 focus:ring-red-600/10 focus:border-red-600 transition-all placeholder:text-gray-200 dark:placeholder:text-gray-700 uppercase"
                    required>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="flex-1 px-4 py-4 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 rounded-xl font-black hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                    Keep Account
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-4 bg-red-600 text-white rounded-xl font-black shadow-lg shadow-red-600/25 hover:bg-red-700 active:scale-[0.98] transition-all">
                    Confirm Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>