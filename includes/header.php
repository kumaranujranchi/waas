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
        <?php echo $pageTitle ?? 'SiteOnSub | WaaS Marketplace'; ?>
    </title>
    <link rel="icon" type="image/png" href="<?php echo baseUrl('assets/images/favicon.png'); ?>">

    <!-- Social Media Meta Tags -->
    <meta property="og:title" content="<?php echo $pageTitle ?? 'SiteOnSub | WaaS Marketplace'; ?>" />
    <meta property="og:description" content="Launch your dream business with our premium, ready-to-use solutions." />
    <meta property="og:image" content="<?php echo baseUrl('assets/images/favicon.png'); ?>" />
    <meta property="og:url"
        content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
    <meta property="og:type" content="website" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="<?php echo $pageTitle ?? 'SiteOnSub | WaaS Marketplace'; ?>" />
    <meta name="twitter:description" content="Launch your dream business with our premium, ready-to-use solutions." />
    <meta name="twitter:image" content="<?php echo baseUrl('assets/images/favicon.png'); ?>" />
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
                <a href="<?php echo baseUrl('index.php'); ?>">
                    <img src="<?php echo baseUrl('assets/images/logo.png'); ?>" alt="SiteOnSub Logo"
                        class="h-10 w-auto">
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
                    <?php if (isAdmin()): ?>
                        <a href="<?php echo baseUrl('admin/index.php'); ?>"
                            class="hidden sm:flex items-center gap-1 text-sm font-bold text-primary px-3 py-1 bg-primary/10 rounded-full">
                            <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                            Admin Panel
                        </a>
                    <?php else: ?>
                        <a href="<?php echo baseUrl('dashboard/index.php'); ?>"
                            class="hidden sm:block text-sm font-medium text-[#0f0e1b] dark:text-white/80">
                            <?php echo e($currentUser['full_name']); ?>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo baseUrl('auth/logout.php'); ?>"
                        class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-accent-green text-white text-sm font-bold shadow-sm hover:opacity-90 transition-all">
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