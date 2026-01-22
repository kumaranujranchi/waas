<?php
/**
 * Admin Settings Page
 */

$pageTitle = 'Profile Settings';
include __DIR__ . '/includes/header.php';

require_once __DIR__ . '/../models/User.php';

$userId = $_SESSION['user_id'];
$userModel = new User();
$user = $userModel->getUserById($userId);

$error = '';
$success = '';

// Handle forms
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if (empty($fullName)) {
            $error = 'Full name is required';
        } else {
            $updateData = ['full_name' => $fullName, 'phone' => $phone];

            // Handle avatar upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileExtension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $fileName = 'admin_' . $userId . '_' . time() . '.' . $fileExtension;
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
                    $updateData['avatar'] = 'uploads/avatars/' . $fileName;
                }
            }

            if ($userModel->updateProfile($userId, $updateData)) {
                $success = 'Profile updated successfully!';
                // Refresh data
                $user = $userModel->getUserById($userId);
                $_SESSION['user_full_name'] = $user['full_name'];
            } else {
                $error = 'Failed to update profile';
            }
        }
    } elseif ($action === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            $error = 'All password fields are required';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Passwords do not match';
        } elseif (strlen($newPassword) < 8) {
            $error = 'New password must be at least 8 characters';
        } else {
            $result = $userModel->changePassword($userId, $currentPassword, $newPassword);
            if ($result['success']) {
                $success = 'Password updated successfully!';
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>

<div class="p-8 max-w-5xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">
        <a href="index.php" class="hover:text-primary transition-colors">Admin</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-gray-600 dark:text-gray-300">Settings</span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-2">My Account</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Manage your personal information and security
                settings.</p>
        </div>
    </div>

    <!-- Notifications -->
    <?php if ($success): ?>
        <div
            class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-2xl border border-green-100 dark:border-green-900/30 flex items-center gap-3">
            <span class="material-symbols-outlined uppercase">check_circle</span>
            <p class="font-bold sm:text-base text-sm">
                <?php echo $success; ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div
            class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-2xl border border-red-100 dark:border-red-900/30 flex items-center gap-3">
            <span class="material-symbols-outlined uppercase">error</span>
            <p class="font-bold sm:text-base text-sm">
                <?php echo $error; ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"
        x-data="{ activeTab: window.location.hash === '#security' ? 'security' : 'profile' }">
        <!-- Sidebar Navigation -->
        <div class="space-y-2">
            <button @click="activeTab = 'profile'; window.location.hash = 'profile'"
                :class="activeTab === 'profile' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white dark:bg-white/5 text-gray-500 hover:bg-gray-50 dark:hover:bg-white/10'"
                class="w-full flex items-center gap-3 px-6 py-4 rounded-2xl transition-all font-bold text-sm text-left">
                <span class="material-symbols-outlined">person</span>
                Profile Information
            </button>
            <button @click="activeTab = 'security'; window.location.hash = 'security'"
                :class="activeTab === 'security' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white dark:bg-white/5 text-gray-500 hover:bg-gray-50 dark:hover:bg-white/10'"
                class="w-full flex items-center gap-3 px-6 py-4 rounded-2xl transition-all font-bold text-sm text-left">
                <span class="material-symbols-outlined">lock</span>
                Security & Password
            </button>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-2">
            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <form method="POST" enctype="multipart/form-data"
                    class="bg-white dark:bg-[#1c1b2e] rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="p-8 border-b border-gray-100 dark:border-white/5">
                        <h3 class="text-xl font-black text-gray-800 dark:text-white">Profile Details</h3>
                    </div>

                    <div class="p-8 space-y-8">
                        <!-- Avatar Upload -->
                        <div class="flex flex-col sm:flex-row items-center gap-8">
                            <div class="relative group">
                                <div
                                    class="size-24 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-bold text-3xl border-2 border-white dark:border-white/5 shadow-md overflow-hidden">
                                    <?php if (!empty($user['avatar'])): ?>
                                        <img id="avatar-preview" src="<?php echo baseUrl($user['avatar']); ?>"
                                            class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span id="avatar-initial">
                                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <label
                                    class="absolute -bottom-2 -right-2 size-8 bg-white dark:bg-background-dark rounded-full shadow-lg border border-gray-200 dark:border-white/10 flex items-center justify-center cursor-pointer hover:bg-primary hover:text-white transition-all">
                                    <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                                    <input type="file" name="avatar" class="hidden" accept="image/*"
                                        onchange="previewAvatar(this)">
                                </label>
                            </div>
                            <div class="text-center sm:text-left">
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Profile Picture</p>
                                <p class="text-xs text-gray-500 mt-1 uppercase tracking-tight font-black">PNG, JPG or
                                    WEBP (Max 2MB)</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Full
                                    Name</label>
                                <input type="text" name="full_name" value="<?php echo e($user['full_name']); ?>"
                                    required
                                    class="w-full px-5 py-3.5 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Phone
                                    Number</label>
                                <input type="tel" name="phone" value="<?php echo e($user['phone']); ?>"
                                    class="w-full px-5 py-3.5 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Email
                                Address (Read-only)</label>
                            <input type="email" value="<?php echo e($user['email']); ?>" disabled
                                class="w-full px-5 py-3.5 bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl text-sm font-bold text-gray-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="p-8 bg-gray-50/50 dark:bg-white/5 flex justify-end">
                        <button type="submit"
                            class="px-8 py-3.5 bg-primary text-white rounded-2xl font-black shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-cloak>
                <form method="POST"
                    class="bg-white dark:bg-[#1c1b2e] rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                    <input type="hidden" name="action" value="change_password">

                    <div class="p-8 border-b border-gray-100 dark:border-white/5">
                        <h3 class="text-xl font-black text-gray-800 dark:text-white">Account Security</h3>
                    </div>

                    <div class="p-8 space-y-6">
                        <div
                            class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-2xl border border-blue-100 dark:border-blue-900/20 flex gap-4 items-start">
                            <span class="material-symbols-outlined text-blue-500">info</span>
                            <p class="text-xs text-blue-700 dark:text-blue-300 font-bold leading-relaxed">
                                Updating your password will not log you out of your current session. Make sure to use a
                                strong, unique password.
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Current
                                    Password</label>
                                <input type="password" name="current_password" required
                                    class="w-full px-5 py-3.5 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">New
                                        Password</label>
                                    <input type="password" name="new_password" required
                                        class="w-full px-5 py-3.5 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Confirm
                                        New Password</label>
                                    <input type="password" name="confirm_password" required
                                        class="w-full px-5 py-3.5 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-gray-50/50 dark:bg-white/5 flex justify-end">
                        <button type="submit"
                            class="px-8 py-3.5 bg-primary text-white rounded-2xl font-black shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('avatar-preview');
                const initial = document.getElementById('avatar-initial');

                if (preview) {
                    preview.src = e.target.result;
                } else if (initial) {
                    const img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    initial.parentNode.appendChild(img);
                    initial.remove();
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>