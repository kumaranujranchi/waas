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
$currentPage = basename($_SERVER['PHP_SELF']);
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

    <!-- International Telephone Input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <!-- GSAP for Premium Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        .iti {
            width: 100%;
        }

        .iti__flag {
            background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/img/flags.png");
        }

        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .iti__flag {
                background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/img/flags@2x.png");
            }
        }

        /* Hide nav for GSAP pop-in */
        nav a,
        .flex.items-center.gap-4 {
            opacity: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to(['nav a', '.flex.items-center.gap-4 > *'], {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.1,
                ease: 'power4.out',
                startAt: { y: 20 }
            });
        });
    </script>

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
            position: relative;
        }

        /* Premium Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background:
                radial-gradient(circle at 20% 50%, rgba(80, 72, 229, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(59, 130, 246, 0.06) 0%, transparent 50%);
            background-color: #f6f6f8;
        }

        body.dark::before {
            background:
                radial-gradient(circle at 20% 50%, rgba(80, 72, 229, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            background-color: #121121;
        }

        /* Subtle Grid Pattern */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image:
                linear-gradient(rgba(80, 72, 229, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(80, 72, 229, 0.05) 1px, transparent 1px);
            background-size: 100px 100px;
            opacity: 1;
        }

        body.dark::after {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            opacity: 1;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#0f0e1b] dark:text-white font-display">

    <!-- Navigation -->
    <header
        class="sticky top-0 z-50 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-solid border-[#e8e8f3] dark:border-white/10">
        <div class="max-w-[1200px] mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="<?php echo baseUrl('index.php'); ?>">
                    <img src="<?php echo baseUrl('assets/images/logo.png'); ?>" alt="SiteOnSub Logo"
                        class="h-10 w-auto">
                </a>
            </div>
            <nav class="hidden md:flex items-center gap-6">
                <a class="<?php echo $currentPage === 'index.php' ? 'text-primary' : 'text-[#0f0e1b] dark:text-white/80'; ?> text-sm font-medium hover:text-primary transition-colors"
                    href="<?php echo baseUrl('dashboard/index.php'); ?>">Dashboard</a>
                <a class="<?php echo $currentPage === 'subscriptions.php' ? 'text-primary' : 'text-[#0f0e1b] dark:text-white/80'; ?> text-sm font-medium hover:text-primary transition-colors"
                    href="<?php echo baseUrl('dashboard/subscriptions.php'); ?>">Subscriptions</a>
                <a class="<?php echo $currentPage === 'orders.php' ? 'text-primary' : 'text-[#0f0e1b] dark:text-white/80'; ?> text-sm font-medium hover:text-primary transition-colors"
                    href="<?php echo baseUrl('dashboard/orders.php'); ?>">Orders</a>
                <a class="<?php echo $currentPage === 'billing.php' ? 'text-primary' : 'text-[#0f0e1b] dark:text-white/80'; ?> text-sm font-medium hover:text-primary transition-colors"
                    href="<?php echo baseUrl('dashboard/billing.php'); ?>">Billing</a>
                <a class="<?php echo $currentPage === 'profile.php' || $currentPage === 'account.php' ? 'text-primary' : 'text-[#0f0e1b] dark:text-white/80'; ?> text-sm font-medium hover:text-primary transition-colors"
                    href="<?php echo baseUrl('dashboard/settings/profile.php'); ?>">Settings</a>
            </nav>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <?php if (!empty($currentUser['avatar'])): ?>
                        <img src="<?php echo e($currentUser['avatar']); ?>"
                            class="w-8 h-8 rounded-full border border-gray-200 dark:border-white/10">
                    <?php else: ?>
                        <div
                            class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold">
                            <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <span
                        class="hidden sm:block text-sm font-medium"><?php echo e(explode(' ', $currentUser['full_name'])[0]); ?></span>
                </div>
                <a href="<?php echo baseUrl('auth/logout.php'); ?>"
                    class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-accent-green text-white text-sm font-bold shadow-sm hover:opacity-90 transition-all">
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen pt-8 pb-12">
        <div class="max-w-[1200px] mx-auto px-6">