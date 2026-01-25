<?php
/**
 * Homepage - Dynamic Product Listing
 */

// Include header
include __DIR__ . '/includes/header.php';

// Include models
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Category.php';

// Initialize models
$productModel = new Product();
$categoryModel = new Category();

// Get categories
$categories = $categoryModel->getAllCategories();

// Get featured products
$featuredProducts = $productModel->getFeaturedProducts(4);

// Handle search
$searchQuery = $_GET['search'] ?? '';
$selectedCategory = $_GET['category'] ?? '';

if ($searchQuery) {
    $products = $productModel->searchProducts($searchQuery);
} elseif ($selectedCategory) {
    $products = $productModel->getProductsByCategory($selectedCategory);
} else {
    $products = $productModel->getAllProducts(16); // Show up to 16 products
}

// Split products for different sections
$featuredSectionProducts = array_slice($products, 0, 8); // First 8 for Featured Solutions
$moreSolutionsProducts = array_slice($products, 8); // Remaining for More Solutions section
?>

<main>
    <!-- Hero Section -->
    <div
        class="relative bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-background-dark dark:via-purple-900/10 dark:to-pink-900/10 overflow-hidden">
        <div class="max-w-[1200px] mx-auto px-6 py-20 md:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div class="flex flex-col gap-8 relative z-10">
                    <div class="flex flex-col gap-6">
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary text-sm font-bold uppercase tracking-wider w-fit border border-primary/20">
                            Ready-to-Launch Solutions
                        </span>
                        <h1
                            class="text-[#0f0e1b] dark:text-white text-4xl md:text-6xl font-black leading-[1.1] tracking-tight">
                            Premium Websites<br>
                            & Business Software<br>
                            <span class="text-primary">Without Development Cost</span>
                        </h1>
                        <p class="text-[#545095] dark:text-white/70 text-xl font-medium max-w-[540px] leading-relaxed">
                            Launch your professional online presence in affordable cost with our curated service of
                            high-performance WaaS solutions.
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center gap-5">
                        <a href="#solutions"
                            class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-xl h-14 px-6 bg-primary text-white text-base font-bold shadow-lg hover:shadow-primary/30 transition-all">
                            View Solutions
                        </a>
                        <a class="flex items-center gap-2 text-primary font-bold hover:underline"
                            href="<?php echo baseUrl('consultation.php'); ?>">
                            Book Free Consultation <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-6 pt-4">
                        <div class="flex -space-x-3">
                            <div
                                class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-purple-400 to-pink-400">
                            </div>
                            <div
                                class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-blue-400 to-cyan-400">
                            </div>
                            <div
                                class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-green-400 to-emerald-400">
                            </div>
                        </div>
                        <p class="text-sm text-[#545095] dark:text-white/60 font-medium">Joined by 2,000+ businesses
                            worldwide</p>
                    </div>
                </div>

                <!-- Right Visual with Floating Cards -->
                <div class="relative h-[500px] lg:h-[600px]">
                    <!-- Main Dashboard Preview -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
                        <div class="relative rounded-3xl shadow-2xl overflow-hidden group">
                            <img src="assets/images/hero_dashboard.jpg" alt="Dashboard Preview"
                                class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- Floating Card 1 - Trophy -->
                    <div
                        class="absolute top-8 left-0 bg-white dark:bg-white/10 backdrop-blur-lg rounded-2xl p-4 shadow-xl border border-gray-100 dark:border-white/20 animate-float">
                        <div class="flex items-center gap-3">
                            <div class="text-4xl">🏆</div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400">#1</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Best WaaS Platform</p>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Card 2 - Stats -->
                    <div
                        class="absolute top-32 right-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 shadow-xl animate-float-delayed">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-white text-sm">trending_up</span>
                            <p class="text-xs font-bold text-white/80">Revenue Increase</p>
                        </div>
                        <p class="text-3xl font-black text-white">5x</p>
                        <p class="text-xs text-white/80 mt-1">Average growth</p>
                    </div>

                    <!-- Floating Card 3 - Users -->
                    <div
                        class="absolute bottom-8 left-4 bg-white dark:bg-white/10 backdrop-blur-lg rounded-2xl p-5 shadow-xl border border-gray-100 dark:border-white/20 animate-float">
                        <div class="flex -space-x-2 mb-3">
                            <div
                                class="w-8 h-8 rounded-full border-2 border-white bg-gradient-to-br from-purple-400 to-pink-400">
                            </div>
                            <div
                                class="w-8 h-8 rounded-full border-2 border-white bg-gradient-to-br from-blue-400 to-cyan-400">
                            </div>
                            <div
                                class="w-8 h-8 rounded-full border-2 border-white bg-gradient-to-br from-green-400 to-emerald-400">
                            </div>
                        </div>
                        <p class="text-2xl font-black text-gray-900 dark:text-white">2000+</p>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400">Satisfied Users</p>
                    </div>

                    <!-- Floating Card 4 - Features -->
                    <div
                        class="absolute bottom-32 right-4 bg-white dark:bg-white/10 backdrop-blur-lg rounded-xl p-4 shadow-xl border border-gray-100 dark:border-white/20 animate-float-delayed">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">AI Powered</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-500 text-sm">security</span>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">100% Secure</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-purple-500 text-sm">support_agent</span>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">24/7 Support</p>
                            </div>
                        </div>
                    </div>

                    <!-- Background Decorations -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-purple-500/20 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes float-delayed {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float-delayed 6s ease-in-out infinite 1s;
        }

        .bg-grid-white\/10 {
            background-image: linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
        }
    </style>

    <!-- Marketplace Browsing -->
    <div class="bg-white dark:bg-background-dark/50 py-16 border-y border-[#e8e8f3] dark:border-white/10"
        id="solutions">
        <div class="max-w-[1200px] mx-auto px-6">
            <!-- Search & Filters -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div class="w-full max-w-md">
                    <form method="GET" action="">
                        <label class="flex flex-col w-full">
                            <div
                                class="flex w-full flex-1 items-stretch rounded-xl h-12 bg-background-light dark:bg-white/5 border border-[#e8e8f3] dark:border-white/10 focus-within:border-primary transition-all">
                                <div class="text-[#545095] flex items-center justify-center pl-4">
                                    <span class="material-symbols-outlined">search</span>
                                </div>
                                <input
                                    class="form-input flex w-full border-none bg-transparent focus:ring-0 text-[#0f0e1b] dark:text-white placeholder:text-[#545095]/60 px-4 text-base font-normal"
                                    name="search" placeholder="Search for websites or software..."
                                    value="<?php echo e($searchQuery); ?>" />
                            </div>
                        </label>
                    </form>
                </div>
                <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                    <a href="<?php echo baseUrl('index.php'); ?>"
                        class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full <?php echo !$selectedCategory ? 'bg-primary text-white' : 'bg-background-light dark:bg-white/5 text-[#0f0e1b] dark:text-white hover:bg-[#e8e8f3] dark:hover:bg-white/10'; ?> px-5 transition-all">
                        <span class="text-sm font-medium">All Solutions</span>
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="?category=<?php echo $category['id']; ?>"
                            class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full <?php echo $selectedCategory == $category['id'] ? 'bg-primary text-white' : 'bg-background-light dark:bg-white/5 text-[#0f0e1b] dark:text-white hover:bg-[#e8e8f3] dark:hover:bg-white/10'; ?> px-5 transition-all">
                            <span class="text-sm font-medium">
                                <?php echo e($category['name']); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex items-center justify-between mb-8">
                <h2 class="text-[#0f0e1b] dark:text-white text-2xl font-bold tracking-tight">
                    <?php echo $searchQuery ? 'Search Results' : ($selectedCategory ? 'Filtered Solutions' : 'Featured Solutions'); ?>
                </h2>
                <span class="text-primary text-sm font-bold">
                    <?php echo count($featuredSectionProducts); ?> Solutions
                </span>
            </div>

            <!-- Solution Grid -->
            <div
                class="flex md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8 overflow-x-auto md:overflow-visible snap-x snap-mandatory pb-8 md:pb-0 -mx-6 px-6 md:mx-0 md:px-0">
                <?php foreach ($featuredSectionProducts as $product): ?>
                    <a href="<?php echo baseUrl('product-detail.php?slug=' . $product['slug']); ?>"
                        class="card-reveal min-w-[88vw] sm:min-w-[380px] md:min-w-0 snap-center group relative bg-white dark:bg-[#1a1c2e] rounded-3xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500">

                        <!-- Image Container -->
                        <div class="aspect-[4/3] w-full bg-[#f0f2f9] dark:bg-white/5 relative overflow-hidden">
                            <?php
                            $imageUrl = $product['image_url'];
                            // Fix for old query/domain
                            $imageUrl = str_replace(['https://honestchoicereview.com', 'http://honestchoicereview.com'], SITE_URL, $imageUrl);

                            if (!empty($imageUrl)) {
                                if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                    $imageUrl = baseUrl($imageUrl);
                                }
                            }
                            ?>
                            <?php if (!empty($imageUrl)): ?>
                                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($product['name']); ?>"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display:none;"
                                    class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-white/5">
                                    <span class="material-symbols-outlined text-gray-300 text-6xl">image</span>
                                </div>
                            <?php else: ?>
                                <div class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-white/5">
                                    <span class="material-symbols-outlined text-gray-300 text-6xl">image</span>
                                </div>
                            <?php endif; ?>

                            <!-- Gradient Overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <!-- Badge -->
                            <?php if ($product['badge']): ?>
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="px-3 py-1.5 bg-white/95 dark:bg-black/80 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white shadow-lg">
                                        <?php echo e($product['badge']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-6">
                                <h3
                                    class="text-xl font-black text-[#0f0e1b] dark:text-white mb-2 line-clamp-1 group-hover:text-primary transition-colors">
                                    <?php echo e($product['name']); ?>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 h-10 leading-relaxed">
                                    <?php echo e($product['short_description']); ?>
                                </p>
                            </div>

                            <!-- Divider -->
                            <div
                                class="h-px w-full bg-gray-100 dark:bg-white/5 mb-6 group-hover:bg-primary/10 transition-colors">
                            </div>

                            <!-- Footer -->
                            <div class="flex items-end justify-between">
                                <?php
                                $plans = $productModel->getProductPricingPlans($product['id']);
                                $minPrice = !empty($plans) ? min(array_column($plans, 'price')) : 0;
                                ?>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Starting
                                        from</p>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-black text-primary">
                                            <?php echo formatPrice($minPrice); ?>
                                        </span>
                                        <span class="text-xs font-bold text-gray-400">/mo</span>
                                    </div>
                                </div>

                                <button
                                    class="size-12 rounded-full bg-gray-50 dark:bg-white/5 text-[#0f0e1b] dark:text-white flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:shadow-primary/30">
                                    <span
                                        class="material-symbols-outlined transform group-hover:rotate-[-45deg] transition-transform duration-300">arrow_forward</span>
                                </button>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (empty($featuredSectionProducts)): ?>
                <div class="text-center py-16">
                    <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search_off</span>
                    <p class="text-gray-500 text-lg">No products found. Try a different search or category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Consultation Banner -->
    <!-- WaaS Comparison Section -->
    <!-- WaaS Comparison Section -->
    <section class="py-24 relative overflow-hidden bg-gray-50/50 dark:bg-[#0f0e1b]">
        <!-- Background Pattern (Dots) -->
        <div
            class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px] dark:bg-[radial-gradient(#ffffff05_1px,transparent_1px)] opacity-70 pointer-events-none">
        </div>

        <!-- Animated Blobs -->
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[100px] animate-pulse pointer-events-none">
        </div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-500/10 rounded-full blur-[100px] animate-pulse pointer-events-none"
            style="animation-delay: 2s;"></div>

        <div class="max-w-[1200px] mx-auto px-6 relative z-10 transition-all duration-1000">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-20 reveal opacity-0 translate-y-8 transition-all duration-700">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-primary/10 text-primary font-bold text-xs uppercase tracking-widest mb-4 border border-primary/20">Comparison</span>
                <h2 class="text-3xl md:text-5xl font-black text-[#0f0e1b] dark:text-white mb-6 leading-tight">
                    Why Website as a Service<br><span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">Makes More
                        Sense Today</span>
                </h2>
                <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
                    Building a website the traditional way is often slow, expensive, and stressful.
                    <strong>WaaS</strong> is the modern alternative designed for speed, affordability, and peace of
                    mind.
                </p>
            </div>

            <!-- Comparison Cards with WOW Factor -->
            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 mb-20 max-w-5xl mx-auto items-center">

                <!-- VS Badge (Desktop Only) -->
                <div
                    class="hidden md:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 flex-col items-center justify-center h-full pointer-events-none">
                    <div
                        class="w-px h-full bg-gradient-to-b from-transparent via-gray-300 dark:via-white/20 to-transparent">
                    </div>
                    <div
                        class="absolute bg-white dark:bg-[#1a1c2e] border border-gray-200 dark:border-white/10 rounded-full w-10 h-10 flex items-center justify-center shadow-lg">
                        <span class="text-xs font-black text-gray-400">VS</span>
                    </div>
                </div>

                <!-- Traditional (Left Card) -->
                <div
                    class="reveal w-full max-w-xs mx-auto md:max-w-none md:mx-0 opacity-0 -translate-x-8 transition-all duration-1000 group relative bg-white/90 dark:bg-[#151725]/90 rounded-3xl p-6 md:p-10 border border-dashed border-gray-300 dark:border-white/10 overflow-hidden hover:border-gray-400 dark:hover:border-white/20 transition-colors md:scale-95">
                    <!-- Graph Paper Pattern -->
                    <div class="absolute inset-0 opacity-[0.03] animate-pan"
                        style="background-image: linear-gradient(#000 1px, transparent 1px), linear-gradient(90deg, #000 1px, transparent 1px); background-size: 20px 20px;">
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <div class="flex flex-col items-center text-center mb-8 md:mb-10">
                            <div
                                class="size-20 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-6 shadow-inner text-gray-400">
                                <span class="material-symbols-outlined text-4xl">history</span>
                            </div>
                            <h3 class="text-xl md:text-2xl font-black text-gray-500 dark:text-gray-400 mb-1">Traditional
                                Web</h3>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400">The Hard Way</p>
                        </div>

                        <ul
                            class="space-y-4 md:space-y-5 flex flex-col items-center md:items-start text-center md:text-left">
                            <li
                                class="flex items-center gap-4 group/item opacity-60 hover:opacity-100 transition-opacity">
                                <span
                                    class="material-symbols-outlined text-red-400 text-2xl">radio_button_unchecked</span>
                                <span class="text-gray-500 dark:text-gray-400 font-medium text-base md:text-lg">High
                                    Upfront Cost</span>
                            </li>
                            <li
                                class="flex items-center gap-4 group/item opacity-60 hover:opacity-100 transition-opacity">
                                <span
                                    class="material-symbols-outlined text-red-400 text-2xl">radio_button_unchecked</span>
                                <span class="text-gray-500 dark:text-gray-400 font-medium text-base md:text-lg">Static
                                    One-Time Delivery</span>
                            </li>
                            <li
                                class="flex items-center gap-4 group/item opacity-60 hover:opacity-100 transition-opacity">
                                <span
                                    class="material-symbols-outlined text-red-400 text-2xl">radio_button_unchecked</span>
                                <span class="text-gray-500 dark:text-gray-400 font-medium text-base md:text-lg">Paid
                                    Maintenance</span>
                            </li>
                            <li
                                class="flex items-center gap-4 group/item opacity-60 hover:opacity-100 transition-opacity">
                                <span
                                    class="material-symbols-outlined text-red-400 text-2xl">radio_button_unchecked</span>
                                <span class="text-gray-500 dark:text-gray-400 font-medium text-base md:text-lg">Updates
                                    are Expensive</span>
                            </li>
                            <li
                                class="flex items-center gap-4 group/item opacity-60 hover:opacity-100 transition-opacity">
                                <span
                                    class="material-symbols-outlined text-red-400 text-2xl">radio_button_unchecked</span>
                                <span class="text-gray-500 dark:text-gray-400 font-medium text-base md:text-lg">Weeks to
                                    Launch</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- WaaS (Right Card - Premium) -->
                <div
                    class="reveal w-full max-w-xs mx-auto md:max-w-none md:mx-0 opacity-0 translate-x-8 transition-all duration-1000 delay-200 group relative bg-[#0f0e1b] rounded-3xl md:rounded-[2.5rem] p-[2px] overflow-visible shadow-2xl shadow-primary/25 hover:shadow-primary/40 hover:-translate-y-2 transition-all duration-500 z-10 md:scale-105">

                    <!-- Floating Recommended Badge -->
                    <div
                        class="absolute -top-4 -right-2 md:-right-4 z-20 rotate-3 transform hover:rotate-6 transition-transform">
                        <span
                            class="flex items-center gap-1 bg-gradient-to-r from-primary to-purple-600 text-white text-[10px] md:text-xs font-black px-4 py-2 rounded-full shadow-lg border-2 border-white dark:border-[#0f0e1b]">
                            <span class="material-symbols-outlined text-sm">star</span> RECOMMENDED
                        </span>
                    </div>

                    <!-- Border Gradient -->
                    <div class="absolute inset-0 bg-[#5048E5] rounded-[2.5rem] opacity-100"></div>

                    <div
                        class="relative bg-white dark:bg-[#1a1c2e] rounded-[2.4rem] p-6 md:p-12 h-full overflow-hidden">
                        <!-- Glass Shine -->
                        <div
                            class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-gradient-to-br from-primary/10 to-transparent rounded-full blur-3xl pointer-events-none">
                        </div>

                        <div class="relative z-10">
                            <div class="flex flex-col items-center text-center mb-8 md:mb-10">
                                <div
                                    class="size-24 rounded-2xl bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center mb-6 shadow-xl shadow-primary/30 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                    <span class="material-symbols-outlined text-white text-5xl">rocket_launch</span>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">Website
                                    as a Service</h3>
                                <p class="text-xs font-bold uppercase tracking-widest text-primary">The Future is Here
                                </p>
                            </div>

                            <ul
                                class="space-y-4 md:space-y-6 flex flex-col items-center md:items-start text-center md:text-left">
                                <li class="flex items-center gap-4">
                                    <span
                                        class="material-symbols-outlined text-green-500 text-3xl drop-shadow-sm group-hover:scale-110 transition-transform">check_circle</span>
                                    <span class="text-[#0f0e1b] dark:text-white font-bold text-lg md:text-xl">Zero
                                        Upfront Cost</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <span
                                        class="material-symbols-outlined text-green-500 text-3xl drop-shadow-sm group-hover:scale-110 transition-transform">check_circle</span>
                                    <span class="text-[#0f0e1b] dark:text-white font-bold text-lg md:text-xl">Simple
                                        Subscription</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <span
                                        class="material-symbols-outlined text-green-500 text-3xl drop-shadow-sm group-hover:scale-110 transition-transform">check_circle</span>
                                    <span class="text-[#0f0e1b] dark:text-white font-bold text-lg md:text-xl">Hosting
                                        Included</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <span
                                        class="material-symbols-outlined text-green-500 text-3xl drop-shadow-sm group-hover:scale-110 transition-transform">check_circle</span>
                                    <span class="text-[#0f0e1b] dark:text-white font-bold text-lg md:text-xl">Free
                                        Updates & Support</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <span
                                        class="material-symbols-outlined text-green-500 text-3xl drop-shadow-sm group-hover:scale-110 transition-transform">check_circle</span>
                                    <span class="text-[#0f0e1b] dark:text-white font-bold text-lg md:text-xl">Launch in
                                        Days</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Premium Comparison Table (Desktop) -->
            <div
                class="reveal opacity-0 translate-y-12 transition-all duration-1000 hidden md:block max-w-5xl mx-auto rounded-[2.5rem] bg-gray-50/50 dark:bg-[#1a1c2e]/50 p-6 md:p-8">

                <table class="w-full border-separate border-spacing-y-4">
                    <thead>
                        <tr>
                            <th
                                class="py-4 px-6 text-left text-xs font-black text-gray-400 uppercase tracking-widest pl-10">
                                Feature</th>
                            <th class="py-4 px-6 text-left text-xs font-black text-gray-400 uppercase tracking-widest">
                                Traditional</th>
                            <th
                                class="py-4 px-6 text-left text-xs font-black text-primary uppercase tracking-widest text-center bg-white dark:bg-[#1a1c2e] rounded-t-2xl shadow-sm border-x border-t border-gray-100 dark:border-white/5 relative z-10 w-1/3">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-lg">rocket_launch</span>
                                    <span>Website as a Service</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 -->
                        <tr class="group hover:-translate-y-1 transition-transform duration-300">
                            <td
                                class="bg-white dark:bg-[#151725] rounded-l-2xl py-6 px-10 font-bold text-[#0f0e1b] dark:text-white shadow-sm border-y border-l border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                Upfront Cost</td>
                            <td
                                class="bg-white dark:bg-[#151725] py-6 px-6 shadow-sm border-y border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                <div class="flex items-center gap-2 text-gray-500">
                                    <span class="material-symbols-outlined text-red-500 text-lg">error</span>
                                    <span>Expensive</span>
                                </div>
                            </td>
                            <td
                                class="bg-green-50 dark:bg-green-900/10 rounded-r-2xl py-6 px-6 text-center shadow-lg shadow-green-500/5 border border-green-100 dark:border-green-500/20 relative z-10 w-1/3">
                                <div
                                    class="flex items-center justify-center gap-2 text-green-700 dark:text-green-400 font-bold bg-white/50 dark:bg-black/20 py-2 px-4 rounded-xl w-fit mx-auto">
                                    <span class="material-symbols-outlined text-lg">check_circle</span>
                                    <span>Zero (Included)</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="group hover:-translate-y-1 transition-transform duration-300">
                            <td
                                class="bg-white dark:bg-[#151725] rounded-l-2xl py-6 px-10 font-bold text-[#0f0e1b] dark:text-white shadow-sm border-y border-l border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                Maintenance</td>
                            <td
                                class="bg-white dark:bg-[#151725] py-6 px-6 shadow-sm border-y border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                <div class="flex items-center gap-2 text-gray-500">
                                    <span class="material-symbols-outlined text-orange-400 text-lg">warning</span>
                                    <span>₹5,000+ / year</span>
                                </div>
                            </td>
                            <td
                                class="bg-green-50 dark:bg-green-900/10 rounded-r-2xl py-6 px-6 text-center shadow-lg shadow-green-500/5 border border-green-100 dark:border-green-500/20 relative z-10">
                                <div
                                    class="flex items-center justify-center gap-2 text-green-700 dark:text-green-400 font-bold bg-white/50 dark:bg-black/20 py-2 px-4 rounded-xl w-fit mx-auto">
                                    <span class="material-symbols-outlined text-lg">check_circle</span>
                                    <span>Free Forever</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="group hover:-translate-y-1 transition-transform duration-300">
                            <td
                                class="bg-white dark:bg-[#151725] rounded-l-2xl py-6 px-10 font-bold text-[#0f0e1b] dark:text-white shadow-sm border-y border-l border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                Hosting</td>
                            <td
                                class="bg-white dark:bg-[#151725] py-6 px-6 shadow-sm border-y border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                <div class="flex items-center gap-2 text-gray-500">
                                    <span class="material-symbols-outlined text-orange-400 text-lg">warning</span>
                                    <span>₹3,000+ / year</span>
                                </div>
                            </td>
                            <td
                                class="bg-green-50 dark:bg-green-900/10 rounded-r-2xl py-6 px-6 text-center shadow-lg shadow-green-500/5 border border-green-100 dark:border-green-500/20 relative z-10">
                                <div
                                    class="flex items-center justify-center gap-2 text-green-700 dark:text-green-400 font-bold bg-white/50 dark:bg-black/20 py-2 px-4 rounded-xl w-fit mx-auto">
                                    <span class="material-symbols-outlined text-lg">check_circle</span>
                                    <span>Included</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 4 -->
                        <tr class="group hover:-translate-y-1 transition-transform duration-300">
                            <td
                                class="bg-white dark:bg-[#151725] rounded-l-2xl py-6 px-10 font-bold text-[#0f0e1b] dark:text-white shadow-sm border-y border-l border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                Updates</td>
                            <td
                                class="bg-white dark:bg-[#151725] py-6 px-6 shadow-sm border-y border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                <div class="flex items-center gap-2 text-gray-500">
                                    <span class="material-symbols-outlined text-red-500 text-lg">cancel</span>
                                    <span>Paid per request</span>
                                </div>
                            </td>
                            <td
                                class="bg-green-50 dark:bg-green-900/10 rounded-r-2xl py-6 px-6 text-center shadow-lg shadow-green-500/5 border border-green-100 dark:border-green-500/20 relative z-10">
                                <div
                                    class="flex items-center justify-center gap-2 text-green-700 dark:text-green-400 font-bold bg-white/50 dark:bg-black/20 py-2 px-4 rounded-xl w-fit mx-auto">
                                    <span class="material-symbols-outlined text-lg">check_circle</span>
                                    <span>Unlimited</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 5 -->
                        <tr class="group hover:-translate-y-1 transition-transform duration-300">
                            <td
                                class="bg-white dark:bg-[#151725] rounded-l-2xl py-6 px-10 font-bold text-[#0f0e1b] dark:text-white shadow-sm border-y border-l border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                Launch Time</td>
                            <td
                                class="bg-white dark:bg-[#151725] py-6 px-6 shadow-sm border-y border-gray-100 dark:border-white/5 group-hover:border-primary/20 transition-colors">
                                <div class="flex items-center gap-2 text-gray-500">
                                    <span
                                        class="material-symbols-outlined text-orange-400 text-lg">hourglass_empty</span>
                                    <span>4-8 Weeks</span>
                                </div>
                            </td>
                            <td
                                class="bg-green-50 dark:bg-green-900/10 rounded-r-2xl py-6 px-6 text-center shadow-lg shadow-green-500/5 border border-green-100 dark:border-green-500/20 relative z-10">
                                <div
                                    class="flex items-center justify-center gap-2 text-green-700 dark:text-green-400 font-bold bg-white/50 dark:bg-black/20 py-2 px-4 rounded-xl w-fit mx-auto">
                                    <span class="material-symbols-outlined text-lg">check_circle</span>
                                    <span>~ 7 Days</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Comparison Cards (Accordion Style) -->
            <div class="block md:hidden max-w-sm mx-auto space-y-4">

                <!-- Traditional Card (Collapsed by Default) -->
                <div class="bg-white dark:bg-[#151725] rounded-2xl border border-dashed border-gray-300 dark:border-white/10 overflow-hidden text-center opacity-80"
                    id="traditionalCard">
                    <button
                        onclick="document.getElementById('traditionalContent').classList.toggle('hidden'); document.getElementById('traditionalChevron').classList.toggle('rotate-180')"
                        class="w-full p-4 flex items-center justify-between text-left">
                        <div class="flex items-center gap-3">
                            <div
                                class="size-10 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400">
                                <span class="material-symbols-outlined">history</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-600 dark:text-gray-300">Traditional Web</h3>
                                <p class="text-[10px] uppercase font-bold text-gray-400">The Hard Way</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-gray-400 transition-transform"
                            id="traditionalChevron">expand_more</span>
                    </button>

                    <div id="traditionalContent"
                        class="hidden border-t border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 p-4 text-sm">
                        <ul class="space-y-3">
                            <li class="flex items-center justify-between gap-2">
                                <span class="text-gray-500">Upfront Cost</span>
                                <span class="font-bold text-red-500">Expensive</span>
                            </li>
                            <li class="flex items-center justify-between gap-2">
                                <span class="text-gray-500">Maintenance</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">₹5,000+ / yr</span>
                            </li>
                            <li class="flex items-center justify-between gap-2">
                                <span class="text-gray-500">Updates</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">Paid</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Arrow Divider -->
                <div class="flex justify-center text-gray-300">
                    <span class="material-symbols-outlined text-3xl">arrow_downward</span>
                </div>

                <!-- WaaS Card (Expanded Content) -->
                <div class="bg-[#0f0e1b] rounded-3xl p-1 shadow-xl shadow-primary/20">
                    <div
                        class="bg-white dark:bg-[#1a1c2e] rounded-[1.4rem] p-5 border border-primary/20 overflow-hidden relative">
                        <div
                            class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-primary/10 rounded-full blur-2xl pointer-events-none">
                        </div>

                        <div class="flex items-center gap-3 mb-6 relative z-10">
                            <div
                                class="size-12 rounded-xl bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center text-white shadow-lg">
                                <span class="material-symbols-outlined">rocket_launch</span>
                            </div>
                            <div>
                                <h3 class="font-black text-lg text-[#0f0e1b] dark:text-white">Website as a Service</h3>
                                <p class="text-[10px] uppercase font-bold text-primary">Recommended</p>
                            </div>
                        </div>

                        <ul class="space-y-3 relative z-10">
                            <li
                                class="bg-green-50 dark:bg-green-900/10 p-3 rounded-xl flex items-center gap-3 border border-green-100 dark:border-green-500/20">
                                <span class="material-symbols-outlined text-green-600 text-xl">check_circle</span>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Upfront Cost
                                    </p>
                                    <p class="font-black text-green-700 dark:text-green-400">Zero (Included)</p>
                                </div>
                            </li>
                            <li
                                class="bg-green-50 dark:bg-green-900/10 p-3 rounded-xl flex items-center gap-3 border border-green-100 dark:border-green-500/20">
                                <span class="material-symbols-outlined text-green-600 text-xl">check_circle</span>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Maintenance
                                        & Hosting</p>
                                    <p class="font-black text-green-700 dark:text-green-400">Free Forever</p>
                                </div>
                            </li>
                            <li
                                class="bg-green-50 dark:bg-green-900/10 p-3 rounded-xl flex items-center gap-3 border border-green-100 dark:border-green-500/20">
                                <span class="material-symbols-outlined text-green-600 text-xl">check_circle</span>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Updates</p>
                                    <p class="font-black text-green-700 dark:text-green-400">Unlimited</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Key Takeaway & CTA -->
            <div class="text-center mt-20 reveal opacity-0 translate-y-8 transition-all duration-700 delay-300">
                <p
                    class="text-2xl md:text-3xl font-black text-[#0f0e1b] dark:text-white mb-10 max-w-4xl mx-auto leading-normal">
                    "Websites shouldn't be a burned... they should be a <span
                        class="bg-gradient-to-r from-primary to-purple-600 bg-clip-text text-transparent">growth
                        engine.</span>"
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#solutions"
                        class="group relative overflow-hidden w-full sm:w-auto px-8 py-4 bg-primary text-white rounded-xl font-bold text-lg shadow-xl shadow-primary/30 transition-all hover:scale-105">
                        <span class="relative z-10">See Pricing Plans</span>
                        <div
                            class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        </div>
                    </a>
                    <a href="<?php echo baseUrl('consultation.php'); ?>"
                        class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-white/10 text-[#0f0e1b] dark:text-white border border-gray-200 dark:border-white/10 rounded-xl font-bold text-lg hover:bg-gray-50 dark:hover:bg-white/20 transition-colors">
                        Book Strategy Call
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Wait for header to initialize GSAP/ScrollTrigger sync
            setTimeout(() => {
                // Card Reveal Animation
                const cards = document.querySelectorAll(".card-reveal");
                if (cards.length > 0) {
                    // Hide via JS so they are visible if JS fails
                    gsap.set(cards, { opacity: 0, y: 40 });

                    gsap.to(cards, {
                        scrollTrigger: {
                            trigger: "#solutions",
                            start: "top 80%",
                        },
                        y: 0,
                        opacity: 1,
                        duration: 1,
                        stagger: 0.1,
                        ease: "power3.out"
                    });
                }

                // Standard Reveal (for sections)
                const revealElements = document.querySelectorAll('.reveal');
                revealElements.forEach(el => {
                    gsap.fromTo(el,
                        { opacity: 0, y: 40 },
                        {
                            scrollTrigger: {
                                trigger: el,
                                start: "top 95%",
                            },
                            opacity: 1,
                            y: 0,
                            duration: 0.8,
                            ease: "power2.out"
                        }
                    );
                });
            }, 100);
        });
    </script>

    <!-- More Solutions Section (for products 9+) -->
    <?php if (!empty($moreSolutionsProducts) && !$searchQuery && !$selectedCategory): ?>
        <section class="py-20 bg-white dark:bg-background-dark/50 border-t border-gray-100 dark:border-white/10">
            <div class="max-w-[1200px] mx-auto px-6">
                <!-- Header -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-5xl font-black text-[#0f0e1b] dark:text-white mb-6">
                        More Premium Solutions
                    </h2>
                    <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
                        Explore our complete range of professional websites and business software designed to accelerate
                        your growth.
                    </p>
                </div>

                <!-- Solution Grid -->
                <div
                    class="flex md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8 overflow-x-auto md:overflow-visible snap-x snap-mandatory pb-8 md:pb-0 -mx-6 px-6 md:mx-0 md:px-0">
                    <?php foreach ($moreSolutionsProducts as $product): ?>
                        <a href="<?php echo baseUrl('product-detail.php?slug=' . $product['slug']); ?>"
                            class="card-reveal min-w-[88vw] sm:min-w-[380px] md:min-w-0 snap-center group relative bg-white dark:bg-[#1a1c2e] rounded-3xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500">

                            <!-- Image Container -->
                            <div class="aspect-[4/3] w-full bg-[#f0f2f9] dark:bg-white/5 relative overflow-hidden">
                                <?php
                                $imageUrl = $product['image_url'];
                                // Fix for old query/domain
                                $imageUrl = str_replace(['https://honestchoicereview.com', 'http://honestchoicereview.com'], SITE_URL, $imageUrl);

                                if (!empty($imageUrl)) {
                                    if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                        $imageUrl = baseUrl($imageUrl);
                                    }
                                }
                                ?>
                                <?php if (!empty($imageUrl)): ?>
                                    <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($product['name']); ?>"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="display:none;"
                                        class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-white/5">
                                        <span class="material-symbols-outlined text-gray-300 text-6xl">image</span>
                                    </div>
                                <?php else: ?>
                                    <div class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-white/5">
                                        <span class="material-symbols-outlined text-gray-300 text-6xl">image</span>
                                    </div>
                                <?php endif; ?>

                                <!-- Gradient Overlay -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                <!-- Badge -->
                                <?php if ($product['badge']): ?>
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="px-3 py-1.5 bg-white/95 dark:bg-black/80 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-widest text-[#0f0e1b] dark:text-white shadow-lg">
                                            <?php echo e($product['badge']); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div class="mb-6">
                                    <h3
                                        class="text-xl font-black text-[#0f0e1b] dark:text-white mb-2 line-clamp-1 group-hover:text-primary transition-colors">
                                        <?php echo e($product['name']); ?>
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 h-10 leading-relaxed">
                                        <?php echo e($product['short_description']); ?>
                                    </p>
                                </div>

                                <!-- Divider -->
                                <div
                                    class="h-px w-full bg-gray-100 dark:bg-white/5 mb-6 group-hover:bg-primary/10 transition-colors">
                                </div>

                                <!-- Footer -->
                                <div class="flex items-end justify-between">
                                    <?php
                                    $plans = $productModel->getProductPricingPlans($product['id']);
                                    $minPrice = !empty($plans) ? min(array_column($plans, 'price')) : 0;
                                    ?>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Starting
                                            from</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-2xl font-black text-primary">
                                                <?php echo formatPrice($minPrice); ?>
                                            </span>
                                            <span class="text-xs font-bold text-gray-400">/mo</span>
                                        </div>
                                    </div>

                                    <button
                                        class="size-12 rounded-full bg-gray-50 dark:bg-white/5 text-[#0f0e1b] dark:text-white flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:shadow-primary/30">
                                        <span
                                            class="material-symbols-outlined transform group-hover:rotate-[-45deg] transition-transform duration-300">arrow_forward</span>
                                    </button>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Consultation Banner -->
    <div class="max-w-[1200px] mx-auto px-6 py-20">
        <div class="bg-primary rounded-3xl p-8 md:p-16 relative overflow-hidden flex flex-col items-center text-center">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full -ml-24 -mb-24"></div>
            <h2 class="text-white text-3xl md:text-5xl font-black mb-6 max-w-2xl relative z-10">Not sure which solution
                is right for you?</h2>
            <p class="text-white/80 text-lg md:text-xl mb-10 max-w-xl relative z-10">Get a free 15-minute consultation
                with our experts to find the perfect stack for your business growth.</p>
            <a href="<?php echo baseUrl('consultation.php'); ?>"
                class="bg-white text-primary px-8 py-4 rounded-xl font-bold text-lg hover:bg-accent-green hover:text-white transition-all shadow-xl relative z-10">
                Schedule Your Free Call
            </a>
        </div>
    </div>
    <!-- Key Benefits Section -->
    <section class="py-20 border-t border-gray-100 dark:border-white/5">
        <div class="max-w-[1200px] mx-auto px-6">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-[#0f0e1b] dark:text-white mb-6">
                    A smarter, faster, and stress-free way to build and run your website.
                </h2>
                <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
                    Traditional website development is expensive, slow, and difficult to maintain.
                    <strong>Website as a Service (WaaS)</strong> removes these challenges by giving you a fully managed,
                    high-quality website on a simple subscription model. With WaaS, you focus on your business—we take
                    care of everything else.
                </p>
            </div>

            <!-- Benefits Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
                <!-- Benefit 1 -->
                <div
                    class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div
                        class="size-12 rounded-2xl bg-green-500/10 text-green-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">savings</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">Zero Upfront Cost</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        No need to spend lakhs on development. Start with a low monthly, 6-month, or yearly plan.
                    </p>
                </div>

                <!-- Benefit 2 -->
                <div
                    class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div
                        class="size-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">rocket_launch</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">Faster Go-Live</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Launch your website in days instead of weeks or months.
                    </p>
                </div>

                <!-- Benefit 3 -->
                <div
                    class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div
                        class="size-12 rounded-2xl bg-purple-500/10 text-purple-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">engineering</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">Fully Managed</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Hosting, maintenance, updates, security, and backups are all included.
                    </p>
                </div>

                <!-- Benefit 4 -->
                <div
                    class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div
                        class="size-12 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">trending_up</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">Easy to Scale</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Upgrade features, pages, or plans as your business grows—without rebuilding.
                    </p>
                </div>

                <!-- Benefit 5 -->
                <div
                    class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div
                        class="size-12 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">security</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">Secure & Reliable</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Regular security updates and monitoring keep your website safe and stable.
                    </p>
                </div>

                <!-- Benefit 6 -->
                <div
                    class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div
                        class="size-12 rounded-2xl bg-cyan-500/10 text-cyan-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">sync</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">Always Up-to-Date</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Your website stays modern with continuous improvements and optimizations.
                    </p>
                </div>

                <!-- Benefit 7 -->
                <div
                    class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div
                        class="size-12 rounded-2xl bg-pink-500/10 text-pink-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">support_agent</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">Ongoing Support</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Get expert support whenever you need it, not just at project delivery.
                    </p>
                </div>

                <!-- Benefit 8 -->
                <div
                    class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div
                        class="size-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">payments</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">Predictable Pricing</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        Simple, transparent pricing with no hidden charges or surprise costs.
                    </p>
                </div>
            </div>

            <!-- Closing & CTA -->
            <div class="text-center bg-gray-50 dark:bg-white/5 rounded-[3rem] p-10 md:p-16">
                <h3 class="text-2xl md:text-3xl font-black text-[#0f0e1b] dark:text-white mb-6">
                    Why WaaS Makes More Sense Today
                </h3>
                <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed max-w-3xl mx-auto mb-10">
                    With WaaS, you don’t “buy” a website, you subscribe to a continuously managed digital solution.
                    It’s a modern approach designed for businesses that want flexibility, reliability, and growth
                    without technical headaches.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#solutions"
                        class="w-full sm:w-auto px-8 py-4 bg-primary text-white rounded-xl font-bold text-lg shadow-xl shadow-primary/25 hover:-translate-y-1 transition-transform">
                        Explore Our Plans
                    </a>
                    <a href="<?php echo baseUrl('consultation.php'); ?>"
                        class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-white/10 text-[#0f0e1b] dark:text-white border border-gray-200 dark:border-white/10 rounded-xl font-bold text-lg hover:bg-gray-50 dark:hover:bg-white/20 transition-colors">
                        Book a Free Consultation
                    </a>
                </div>
                <p class="mt-8 text-sm font-bold text-[#0f0e1b] dark:text-white">
                    Let’s build and grow your website the smarter way. 🚀
                </p>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>