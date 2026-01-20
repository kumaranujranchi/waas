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
    $products = $productModel->getAllProducts(8); // Show 8 products
}
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
                    <?php echo count($products); ?> Solutions
                </span>
            </div>

            <!-- Solution Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($products as $product): ?>
                    <a href="<?php echo baseUrl('product-detail.php?slug=' . $product['slug']); ?>"
                        class="group relative bg-white dark:bg-[#1a1c2e] rounded-3xl overflow-hidden border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500">

                        <!-- Image Container -->
                        <div class="aspect-[4/3] w-full bg-[#f0f2f9] dark:bg-white/5 relative overflow-hidden">
                            <?php
                            $imageUrl = $product['image_url'];
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

            <?php if (empty($products)): ?>
                <div class="text-center py-16">
                    <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search_off</span>
                    <p class="text-gray-500 text-lg">No products found. Try a different search or category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Consultation Banner -->
    <!-- WaaS Comparison Section -->
    <section class="py-20 bg-gray-50 dark:bg-black/20">
        <div class="max-w-[1200px] mx-auto px-6">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-[#0f0e1b] dark:text-white mb-6">
                    Why Website as a Service Makes More Sense Today
                </h2>
                <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
                    Building a website the traditional way often means high upfront cost, long timelines, and ongoing
                    maintenance headaches.
                    <strong>Website as a Service (WaaS)</strong> changes this by offering modern, fully managed websites
                    on
                    a simple subscription model.
                </p>
            </div>

            <!-- Comparison Cards with WOW Factor -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16 relative">
                <!-- Animated Background Gradient -->
                <div
                    class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-pink-500/5 rounded-3xl blur-3xl -z-10 animate-pulse">
                </div>

                <!-- Traditional (Left Card) -->
                <div
                    class="group relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-white/5 dark:to-white/10 rounded-3xl p-8 md:p-10 border border-gray-200 dark:border-white/10 hover:scale-[1.02] transition-all duration-500 overflow-hidden">
                    <!-- Subtle Pattern Overlay -->
                    <div class="absolute inset-0 opacity-5"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="size-14 rounded-2xl bg-gradient-to-br from-gray-300 to-gray-400 dark:from-white/20 dark:to-white/10 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
                                <span
                                    class="material-symbols-outlined text-gray-600 dark:text-gray-300 text-3xl">history</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-gray-700 dark:text-gray-200">Traditional Web</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Old School Approach</p>
                            </div>
                        </div>

                        <ul class="space-y-4">
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0 group-hover/item:scale-110 transition-transform">
                                    <span
                                        class="material-symbols-outlined text-red-600 dark:text-red-400 text-sm">close</span>
                                </div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">High upfront development
                                    cost</span>
                            </li>
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0 group-hover/item:scale-110 transition-transform">
                                    <span
                                        class="material-symbols-outlined text-red-600 dark:text-red-400 text-sm">close</span>
                                </div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">One-time project delivery
                                    (static)</span>
                            </li>
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0 group-hover/item:scale-110 transition-transform">
                                    <span
                                        class="material-symbols-outlined text-red-600 dark:text-red-400 text-sm">close</span>
                                </div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">Separate hosting &
                                    maintenance fees</span>
                            </li>
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0 group-hover/item:scale-110 transition-transform">
                                    <span
                                        class="material-symbols-outlined text-red-600 dark:text-red-400 text-sm">close</span>
                                </div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">You manage updates &
                                    security</span>
                            </li>
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0 group-hover/item:scale-110 transition-transform">
                                    <span
                                        class="material-symbols-outlined text-red-600 dark:text-red-400 text-sm">close</span>
                                </div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">Weeks or months to
                                    launch</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- WaaS (Right Card - Premium) -->
                <div
                    class="group relative bg-gradient-to-br from-white via-blue-50/50 to-purple-50/50 dark:from-[#1a1c2e] dark:via-[#1e2140] dark:to-[#1a1c2e] rounded-3xl p-8 md:p-10 border-2 border-primary shadow-2xl shadow-primary/20 hover:scale-[1.02] hover:shadow-3xl hover:shadow-primary/30 transition-all duration-500 overflow-hidden">
                    <!-- Animated Gradient Background -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-primary/10 via-purple-500/10 to-pink-500/10 opacity-50 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <!-- Floating Particles Effect -->
                    <div class="absolute top-10 right-10 w-32 h-32 bg-primary/20 rounded-full blur-3xl animate-pulse">
                    </div>
                    <div
                        class="absolute bottom-10 left-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl animate-pulse delay-1000">
                    </div>

                    <!-- Recommended Badge -->
                    <div
                        class="absolute top-0 right-0 bg-gradient-to-r from-primary to-purple-600 text-white text-xs font-black px-6 py-2.5 rounded-bl-2xl shadow-lg flex items-center gap-2 animate-bounce">
                        <span class="material-symbols-outlined text-sm">star</span>
                        RECOMMENDED
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="size-14 rounded-2xl bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center shadow-xl shadow-primary/50 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                <span class="material-symbols-outlined text-white text-3xl">rocket_launch</span>
                            </div>
                            <div>
                                <h3
                                    class="text-2xl font-black bg-gradient-to-r from-primary to-purple-600 bg-clip-text text-transparent">
                                    Website as a Service</h3>
                                <p class="text-xs text-primary font-bold">The Future is Here</p>
                            </div>
                        </div>

                        <ul class="space-y-4">
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/50 group-hover/item:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-white text-sm">check</span>
                                </div>
                                <span class="text-[#0f0e1b] dark:text-white font-bold">Zero upfront development
                                    cost</span>
                            </li>
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/50 group-hover/item:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-white text-sm">check</span>
                                </div>
                                <span class="text-[#0f0e1b] dark:text-white font-bold">Simple monthly/yearly
                                    subscription</span>
                            </li>
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/50 group-hover/item:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-white text-sm">check</span>
                                </div>
                                <span class="text-[#0f0e1b] dark:text-white font-bold">Hosting & maintenance
                                    included</span>
                            </li>
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/50 group-hover/item:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-white text-sm">check</span>
                                </div>
                                <span class="text-[#0f0e1b] dark:text-white font-bold">Regular updates & support</span>
                            </li>
                            <li class="flex items-start gap-3 group/item">
                                <div
                                    class="mt-1 size-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/50 group-hover/item:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-white text-sm">check</span>
                                </div>
                                <span class="text-[#0f0e1b] dark:text-white font-bold">Faster go-live (Days, not
                                    weeks)</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Premium Comparison Table -->
            <div
                class="hidden md:block bg-gradient-to-br from-white to-gray-50 dark:from-[#1a1c2e] dark:to-[#151729] rounded-3xl border border-gray-100 dark:border-white/10 overflow-hidden shadow-xl mb-16 hover:shadow-2xl transition-shadow duration-500">
                <table class="w-full">
                    <thead>
                        <tr
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-white/10 dark:to-white/5 border-b-2 border-primary/20">
                            <th
                                class="py-6 px-8 text-left text-sm font-black text-gray-600 dark:text-gray-300 uppercase tracking-widest">
                                Feature
                            </th>
                            <th
                                class="py-6 px-8 text-left text-sm font-black text-gray-600 dark:text-gray-300 uppercase tracking-widest">
                                Traditional Development
                            </th>
                            <th
                                class="py-6 px-8 text-left text-sm font-black bg-gradient-to-r from-primary to-purple-600 bg-clip-text text-transparent uppercase tracking-widest">
                                Website as a Service
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-6 px-8 font-bold text-[#0f0e1b] dark:text-white">Upfront Cost</td>
                            <td class="py-6 px-8">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 rounded-full font-bold text-sm">
                                    <span class="material-symbols-outlined text-xs">trending_up</span>
                                    High
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-green-400 to-emerald-600 text-white rounded-full font-black text-sm shadow-lg">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    Zero
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-6 px-8 font-bold text-[#0f0e1b] dark:text-white">Maintenance</td>
                            <td class="py-6 px-8 text-gray-600 dark:text-gray-400 font-medium">Extra Fees</td>
                            <td class="py-6 px-8">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-green-400 to-emerald-600 text-white rounded-full font-black text-sm shadow-lg">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    Included
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-6 px-8 font-bold text-[#0f0e1b] dark:text-white">Hosting</td>
                            <td class="py-6 px-8 text-gray-600 dark:text-gray-400 font-medium">Separate Bill</td>
                            <td class="py-6 px-8">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-green-400 to-emerald-600 text-white rounded-full font-black text-sm shadow-lg">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    Included
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-6 px-8 font-bold text-[#0f0e1b] dark:text-white">Updates</td>
                            <td class="py-6 px-8 text-gray-600 dark:text-gray-400 font-medium">Manual / Paid</td>
                            <td class="py-6 px-8">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-green-400 to-emerald-600 text-white rounded-full font-black text-sm shadow-lg">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    Fully Managed
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-6 px-8 font-bold text-[#0f0e1b] dark:text-white">Time to Launch</td>
                            <td class="py-6 px-8 text-gray-600 dark:text-gray-400 font-medium">Weeks / Months</td>
                            <td class="py-6 px-8">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-gradient-to-r from-green-400 to-emerald-600 text-white rounded-full font-black text-sm shadow-lg">
                                    <span class="material-symbols-outlined text-xs">bolt</span>
                                    Days
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Key Takeaway & CTA -->
            <div class="text-center">
                <p class="text-2xl md:text-3xl font-bold text-[#0f0e1b] dark:text-white mb-10 max-w-4xl mx-auto">
                    "Website as a Service is not cheaper websites — <span class="text-primary">it’s a smarter way to own
                        one.</span>"
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#solutions"
                        class="w-full sm:w-auto px-8 py-4 bg-primary text-white rounded-xl font-bold text-lg shadow-xl shadow-primary/25 hover:-translate-y-1 transition-transform">
                        View Our Plans
                    </a>
                    <a href="<?php echo baseUrl('consultation.php'); ?>"
                        class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-white/10 text-[#0f0e1b] dark:text-white border border-gray-200 dark:border-white/10 rounded-xl font-bold text-lg hover:bg-gray-50 dark:hover:bg-white/20 transition-colors">
                        Book Free Consultation
                    </a>
                </div>
                <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                    Not sure which option is right for you? <a href="<?php echo baseUrl('consultation.php'); ?>"
                        class="text-primary hover:underline">Talk to us.</a>
                </p>
            </div>
        </div>
    </section>

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
                    With WaaS, you don’t “buy” a website—you subscribe to a continuously managed digital solution.
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