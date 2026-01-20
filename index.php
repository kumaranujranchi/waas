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
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>