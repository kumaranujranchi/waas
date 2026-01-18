<?php
/**
 * Header Component
 * Reusable navigation header
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include configuration and functions
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$currentUser = getCurrentUser();
$isLoggedIn = isLoggedIn();
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        <?php echo $pageTitle ?? 'WaaS Marketplace | Premium Websites & Software'; ?>
    </title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
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
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
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

<body class="bg-background-light dark:bg-background-dark text-[#0f0e1b] dark:text-white font-display">

    <!-- Flash Messages -->
    <?php if (hasFlashMessage()):
        $flash = getFlashMessage();
        $bgColor = $flash['type'] === 'success' ? 'bg-green-100 text-green-800' :
            ($flash['type'] === 'error' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800');
        ?>
        <div class="fixed top-4 right-4 z-50 <?php echo $bgColor; ?> px-6 py-4 rounded-lg shadow-lg max-w-md">
            <p class="font-medium">
                <?php echo e($flash['message']); ?>
            </p>
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.fixed.top-4').remove();
            }, 5000);
        </script>
    <?php endif; ?>

    <!-- Navigation -->
    <header
        class="sticky top-0 z-50 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-solid border-[#e8e8f3] dark:border-white/10">
        <div class="max-w-[1200px] mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-xl">layers</span>
                </div>
                <a href="<?php echo baseUrl('index.php'); ?>">
                    <h2 class="text-[#0f0e1b] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">
                        <?php echo SITE_NAME; ?>
                    </h2>
                </a>
            </div>
            <nav class="hidden md:flex items-center gap-8">
                <a class="text-[#0f0e1b] dark:text-white/80 text-sm font-medium hover:text-primary transition-colors"
                    href="<?php echo baseUrl('index.php'); ?>">Websites</a>
                <a class="text-[#0f0e1b] dark:text-white/80 text-sm font-medium hover:text-primary transition-colors"
                    href="<?php echo baseUrl('index.php#pricing'); ?>">Pricing</a>
                <a class="text-[#0f0e1b] dark:text-white/80 text-sm font-medium hover:text-primary transition-colors"
                    href="<?php echo baseUrl('consultation.php'); ?>">Book Consultation</a>
            </nav>
            <div class="flex items-center gap-4">
                <?php if ($isLoggedIn): ?>
                    <a href="<?php echo baseUrl('dashboard/index.php'); ?>"
                        class="hidden sm:block text-sm font-medium text-[#0f0e1b] dark:text-white/80">
                        <?php echo e($currentUser['full_name']); ?>
                    </a>
                    <a href="<?php echo baseUrl('auth/logout.php'); ?>"
                        class="flex min-w-[120px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-accent-green text-white text-sm font-bold shadow-sm hover:opacity-90 transition-all">
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo baseUrl('auth/login.php'); ?>"
                        class="hidden sm:block text-sm font-medium text-[#0f0e1b] dark:text-white/80">Login</a>
                    <a href="<?php echo baseUrl('auth/register.php'); ?>"
                        class="flex min-w-[120px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-accent-green text-white text-sm font-bold shadow-sm hover:opacity-90 transition-all">
                        <span>Get Started</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>