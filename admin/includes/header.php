<?php
/**
 * Admin Header
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        <?php echo $pageTitle ?? 'Admin Panel | SiteOnSub'; ?>
    </title>
    <link rel="icon" type="image/png" href="<?php echo baseUrl('assets/images/favicon.png'); ?>">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#5048e5",
                        "background-light": "#f6f6f8",
                        "background-dark": "#121121",
                        "accent-green": "#10b981",
                    },
                },
            },
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#f8f9fa] dark:bg-background-dark text-[#0f0e1b] dark:text-white" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="w-64 bg-white dark:bg-[#1c1b2e] border-r border-gray-200 dark:border-white/5 flex flex-col fixed md:sticky top-0 h-screen transition-transform duration-300 z-50">
            <div class="p-6 flex items-center justify-between border-b border-gray-100 dark:border-white/5">
                <a href="<?php echo baseUrl('admin/index.php'); ?>">
                    <img src="<?php echo baseUrl('assets/images/logo.png'); ?>" alt="SiteOnSub Logo"
                        class="h-10 w-auto">
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-500">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="<?php echo baseUrl('admin/index.php'); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo strpos($_SERVER['PHP_SELF'], 'admin/index.php') !== false ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="font-bold text-sm">Dashboard</span>
                </a>

                <div class="pt-4 pb-2 px-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Management</p>
                </div>

                <a href="<?php echo baseUrl('admin/products/list.php'); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo strpos($_SERVER['PHP_SELF'], 'products/') !== false ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <span class="font-bold text-sm">Services / Products</span>
                </a>

                <a href="<?php echo baseUrl('admin/orders/list.php'); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo strpos($_SERVER['PHP_SELF'], 'orders/') !== false ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <span class="font-bold text-sm">Orders</span>
                </a>

                <a href="<?php echo baseUrl('admin/users/list.php'); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo strpos($_SERVER['PHP_SELF'], 'users/') !== false ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-bold text-sm">Users</span>
                </a>

                <a href="<?php echo baseUrl('admin/consultations/index.php'); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo strpos($_SERVER['PHP_SELF'], 'consultations/index.php') !== false ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined">perm_phone_msg</span>
                    <span class="font-bold text-sm">Consultations</span>
                </a>

                <a href="<?php echo baseUrl('admin/consultations/calendar.php'); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo strpos($_SERVER['PHP_SELF'], 'consultations/calendar.php') !== false ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <span class="font-bold text-sm">Calendar</span>
                </a>

                <div class="pt-4 pb-2 px-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Settings</p>
                </div>

                <a href="<?php echo baseUrl('admin/settings.php'); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo strpos($_SERVER['PHP_SELF'], 'admin/settings.php') !== false ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="font-bold text-sm">Profile Settings</span>
                </a>

                <a href="<?php echo baseUrl('index.php'); ?>" target="_blank"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                    <span class="material-symbols-outlined">visibility</span>
                    <span class="font-bold text-sm">View Website</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100 dark:border-white/5">
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Logged in as</p>
                    <p class="text-sm font-bold truncate">
                        <?php echo e($currentUser['full_name']); ?>
                    </p>
                    <a href="<?php echo baseUrl('auth/logout.php'); ?>"
                        class="mt-3 flex items-center gap-2 text-xs font-bold text-red-500 hover:underline">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        Logout
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden" x-cloak>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header
                class="h-16 bg-white dark:bg-[#1c1b2e] border-b border-gray-200 dark:border-white/5 px-8 flex items-center justify-between sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true"
                        class="md:hidden size-10 flex items-center justify-center rounded-lg border border-gray-200 dark:border-white/10 text-gray-500">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white truncate">
                        <?php echo $pageTitle ?? 'Dashboard'; ?>
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        class="size-10 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center gap-3 p-1.5 rounded-full hover:bg-gray-50 dark:hover:bg-white/5 transition-all outline-none">
                            <div
                                class="size-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold border-2 border-white dark:border-[#1c1b2e] shadow-sm">
                                <?php if (!empty($currentUser['avatar'])): ?>
                                    <img src="<?php echo e($currentUser['avatar']); ?>"
                                        class="w-full h-full rounded-full object-cover">
                                <?php else: ?>
                                    <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                                <?php endif; ?>
                            </div>
                            <span class="hidden sm:block text-sm font-bold text-gray-700 dark:text-gray-200">
                                <?php echo e(explode(' ', $currentUser['full_name'])[0]); ?>
                            </span>
                            <span class="material-symbols-outlined text-gray-400 text-sm transition-transform"
                                :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#1c1b2e] rounded-2xl shadow-xl border border-gray-100 dark:border-white/10 py-2 z-50"
                            x-cloak>
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-white/5 mb-2">
                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-0.5">
                                    Administrator</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white truncate">
                                    <?php echo e($currentUser['full_name']); ?></p>
                            </div>

                            <a href="<?php echo baseUrl('admin/settings.php'); ?>"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary transition-all">
                                <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                                Edit Profile
                            </a>
                            <a href="<?php echo baseUrl('admin/settings.php#security'); ?>"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary transition-all">
                                <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                                Change Password
                            </a>

                            <div class="h-px bg-gray-100 dark:bg-white/5 my-2"></div>

                            <a href="<?php echo baseUrl('auth/logout.php'); ?>"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <?php if (hasFlashMessage()):
                $flash = getFlashMessage();
                $bgColor = $flash['type'] === 'success' ? 'bg-green-100 text-green-800 border-green-200' :
                    ($flash['type'] === 'error' ? 'bg-red-100 text-red-800 border-red-200' : 'bg-blue-100 text-blue-800 border-blue-200');
                ?>
                <div class="px-8 pt-6">
                    <div class="p-4 rounded-xl border <?php echo $bgColor; ?> flex items-center justify-between">
                        <p class="font-bold text-sm">
                            <?php echo e($flash['message']); ?>
                        </p>
                        <button onclick="this.parentElement.parentElement.remove()"
                            class="material-symbols-outlined text-lg">close</button>
                    </div>
                </div>
            <?php endif; ?>