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
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f8f9fa] dark:bg-background-dark text-[#0f0e1b] dark:text-white">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            class="w-64 bg-white dark:bg-[#1c1b2e] border-r border-gray-200 dark:border-white/5 flex flex-col sticky top-0 h-screen">
            <div class="p-6 flex items-center gap-3 border-b border-gray-100 dark:border-white/5">
                <a href="<?php echo baseUrl('admin/index.php'); ?>">
                    <img src="<?php echo baseUrl('assets/images/logo.png'); ?>" alt="SiteOnSub Logo"
                        class="h-10 w-auto">
                </a>
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

                <div class="pt-4 pb-2 px-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Settings</p>
                </div>

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

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header -->
            <header
                class="h-16 bg-white dark:bg-[#1c1b2e] border-b border-gray-200 dark:border-white/5 px-8 flex items-center justify-between sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button
                        class="md:hidden size-10 flex items-center justify-center rounded-lg border border-gray-200">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                        <?php echo $pageTitle; ?>
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        class="size-10 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-500">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <div
                        class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                        <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
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