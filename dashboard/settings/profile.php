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
        if ($userModel->updateProfile($userId, $fullName, $phone)) {
            $success = 'Profile updated successfully!';
            // Refresh user data
            $_SESSION['user'] = null;
            $user = getCurrentUser();
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
    }
}
        } else {
            $error = 'Failed to update profile. Please try again.';
        }
    }
}
?>

<!-- Profile Settings Header -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white mb-2">Edit Profile</h2>
    <p class="text-gray-600 dark:text-gray-400">Update your personal information</p>
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

<!-- Profile Form -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Profile Card -->
    <div class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10">
        <div class="flex flex-col items-center text-center">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center text-white font-bold text-3xl mb-4">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>
            <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-1">
                <?php echo e($user['full_name']); ?>
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                <?php echo e($user['email']); ?>
            </p>
            <div class="w-full space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Member Since</span>
                    <span class="font-medium text-[#0f0e1b] dark:text-white">
                        <?php echo formatDate($user['created_at']); ?>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Status</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded text-xs font-bold">
                        <?php echo ucfirst($user['status']); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="lg:col-span-2">
        <form method="POST" class="bg-white dark:bg-white/5 rounded-lg p-6 border border-gray-200 dark:border-white/10">
            <div class="space-y-6">
                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">
                        Full Name
                    </label>
                    <input 
                        type="text" 
                        name="full_name" 
                        value="<?php echo e($user['full_name']); ?>"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-white/10 rounded-lg bg-white dark:bg-white/5 text-[#0f0e1b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                        required
                    >
                </div>

                <!-- Email (Read-only) -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        value="<?php echo e($user['email']); ?>"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-white/10 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 cursor-not-allowed"
                        disabled
                    >
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Email cannot be changed</p>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">
                        Phone Number
                    </label>
                    <input 
                        type="tel" 
                        name="phone" 
                        value="<?php echo e($user['phone'] ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-white/10 rounded-lg bg-white dark:bg-white/5 text-[#0f0e1b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="+1 (555) 123-4567"
                    >
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary-dark transition-all flex items-center justify-center gap-2"
                    >
                        <span class="material-symbols-outlined">save</span>
                        Save Changes
                    </button>
                    <a 
                        href="<?php echo baseUrl('dashboard/index.php'); ?>"
                        class="px-6 py-3 border border-gray-300 dark:border-white/20 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition-all"
                    >
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>