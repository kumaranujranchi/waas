<?php
/**
 * Homepage - Dynamic Product Listing
 */

$pageTitle = 'WaaS Marketplace | Premium Websites & Software';

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
    <div class="max-w-[1200px] mx-auto px-6 py-12 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col gap-4">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider w-fit">
                        Ready-to-launch solutions
                    </span>
                    <h1
                        class="text-[#0f0e1b] dark:text-white text-5xl md:text-6xl font-black leading-[1.1] tracking-[-0.033em]">
                        Premium Websites & Business Software — <span class="text-primary">Without Development
                            Cost</span>
                    </h1>
                    <p class="text-[#545095] dark:text-white/60 text-lg md:text-xl font-normal max-w-[540px]">
                        Launch your professional online presence in minutes with our curated collection of
                        high-performance WaaS solutions.
                    </p>
                </div>
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
                <div class="flex items-center gap-6 pt-4 border-t border-[#e8e8f3] dark:border-white/10">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 overflow-hidden"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-300 overflow-hidden"
                            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-400 overflow-hidden"
                            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
                    </div>
                    <p class="text-sm text-[#545095] dark:text-white/60 font-medium">Joined by 2,000+ businesses
                        worldwide</p>
                </div>
            </div>
            <div class="relative">
                <div
                    class="w-full aspect-[4/3] bg-primary/5 rounded-3xl overflow-hidden border border-primary/10 shadow-2xl relative z-10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-9xl">dashboard</span>
                </div>
                <div class="absolute -top-6 -right-6 w-32 h-32 bg-accent-green/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-primary/20 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>

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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($products as $product): ?>
                    <a href="<?php echo baseUrl('product-detail.php?slug=' . $product['slug']); ?>"
                        class="group bg-white dark:bg-white/5 rounded-2xl border border-[#e8e8f3] dark:border-white/10 overflow-hidden hover:shadow-xl hover:shadow-primary/5 transition-all duration-300">
                        <div
                            class="aspect-video w-full bg-[#f0f2f9] dark:bg-white/10 relative overflow-hidden flex items-center justify-center">
                            <?php if ($product['image_url']): ?>
                                <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="material-symbols-outlined text-primary text-6xl">web</span>
                            <?php endif; ?>
                            <?php if ($product['badge']): ?>
                                <div
                                    class="absolute top-3 left-3 bg-white dark:bg-background-dark/80 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">
                                    <?php echo e($product['badge']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-5 flex flex-col gap-4">
                            <div>
                                <h3
                                    class="text-[#0f0e1b] dark:text-white text-lg font-bold group-hover:text-primary transition-colors">
                                    <?php echo e($product['name']); ?>
                                </h3>
                                <p class="text-[#545095] dark:text-white/60 text-sm mt-1 line-clamp-2">
                                    <?php echo e($product['short_description']); ?>
                                </p>
                            </div>
                            <div class="flex items-center justify-between">
                                <?php
                                // Get the lowest price for this product
                                $plans = $productModel->getProductPricingPlans($product['id']);
                                $minPrice = !empty($plans) ? min(array_column($plans, 'price')) : 0;
                                ?>
                                <span class="text-primary font-bold">
                                    <?php echo formatPrice($minPrice); ?>/mo
                                </span>
                                <button
                                    class="px-4 py-2 bg-background-light dark:bg-white/10 rounded-lg text-xs font-bold hover:bg-primary hover:text-white transition-all">
                                    View Details
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