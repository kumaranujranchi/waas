<?php
/**
 * Dashboard Header Component
 * For logged-in users only
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require login before showing dashboard
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . baseUrl('auth/login.php'));
    exit;
}

// Include configuration and functions
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        <?php echo $pageTitle ?? 'Dashboard | SiteOnSub'; ?>
    </title>
    <link rel="icon" type="image/png" href="<?php echo baseUrl('assets/images/favicon.png'); ?>">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent-green: #10b981;
            --background-light: #f8f7ff;
            --background-dark: #0f0e1b;
        }

        * {
            color-scheme: light dark;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-sm fixed h-screen overflow-y-auto">
            <div class="p-6">
                <!-- Logo -->
                <a href="<?php echo baseUrl('index.php'); ?>" class="flex items-center gap-3 mb-8">
                    <img src="<?php echo baseUrl('assets/images/logo.png'); ?>" alt="SiteOnSub Logo" class="h-8 w-auto">
                    <span class="font-bold text-lg text-[#0f0e1b] dark:text-white">SiteOnSub</span>
                </a>

                <!-- User Info -->
                <div class="bg-gradient-to-br from-primary/10 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-4 mb-8">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                            <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo e(explode(' ', $currentUser['full_name'])[0]); ?>
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400"><?php echo e($currentUser['email']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <a href="<?php echo baseUrl('dashboard/index.php'); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-primary text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="<?php echo baseUrl('dashboard/subscriptions.php'); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo basename($_SERVER['PHP_SELF']) === 'subscriptions.php' ? 'bg-primary text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                        <span class="material-symbols-outlined">subscriptions</span>
                        <span class="font-medium">Subscriptions</span>
                    </a>

                    <a href="<?php echo baseUrl('dashboard/orders.php'); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'bg-primary text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        <span class="font-medium">Orders</span>
                    </a>

                    <a href="<?php echo baseUrl('dashboard/billing.php'); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo basename($_SERVER['PHP_SELF']) === 'billing.php' ? 'bg-primary text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                        <span class="material-symbols-outlined">payments</span>
                        <span class="font-medium">Billing</span>
                    </a>

                    <a href="<?php echo baseUrl('dashboard/settings/profile.php'); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'bg-primary text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                        <span class="material-symbols-outlined">person</span>
                        <span class="font-medium">Profile</span>
                    </a>

                    <a href="<?php echo baseUrl('dashboard/settings/account.php'); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo basename($_SERVER['PHP_SELF']) === 'account.php' ? 'bg-primary text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                        <span class="material-symbols-outlined">settings</span>
                        <span class="font-medium">Settings</span>
                    </a>
                </nav>

                <!-- Divider -->
                <hr class="my-6 border-gray-200 dark:border-gray-800">

                <!-- Logout Button -->
                <a href="<?php echo baseUrl('auth/logout.php'); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all w-full">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-medium">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 min-h-screen">
            <!-- Top Bar -->
            <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-10">
                <div class="px-8 py-4 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-[#0f0e1b] dark:text-white">
                        <?php echo $pageTitle ?? 'Dashboard'; ?>
                    </h1>
                    <div class="flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined">dark_mode</span>
                        </button>
                        <!-- Profile Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                <span class="material-symbols-outlined">account_circle</span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(explode(' ', $currentUser['full_name'])[0]); ?></span>
                            </button>
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 hidden group-hover:block">
                                <a href="<?php echo baseUrl('dashboard/settings/profile.php'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 first:rounded-t-lg">
                                    Edit Profile
                                </a>
                                <a href="<?php echo baseUrl('dashboard/settings/account.php'); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Account Settings
                                </a>
                                <hr class="border-gray-200 dark:border-gray-700 my-1">
                                <a href="<?php echo baseUrl('auth/logout.php'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 last:rounded-b-lg">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-8">
