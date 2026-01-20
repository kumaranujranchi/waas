<?php
/**
 * Product Detail Page - Dynamic
 */

// Include header
$pageTitle = 'Product Details | SiteOnSub';
include __DIR__ . '/includes/header.php';

// Include models
require_once __DIR__ . '/models/Product.php';

// Initialize model
$productModel = new Product();

// Get product by slug
$slug = $_GET['slug'] ?? '';

if (!$slug) {
    setFlashMessage('error', 'Product not found');
    redirect(baseUrl('index.php'));
}

$product = $productModel->getProductBySlug($slug);

if (!$product) {
    setFlashMessage('error', 'Product not found');
    redirect(baseUrl('index.php'));
}

// Get product features and pricing plans
$features = $productModel->getProductFeatures($product['id']);
$pricingPlans = $productModel->getProductPricingPlans($product['id']);

// Group plans by type
$plansByType = [
    'monthly' => [],
    'semi_annual' => [],
    'yearly' => []
];

foreach ($pricingPlans as $plan) {
    $plansByType[$plan['plan_type']][] = $plan;
}

// Current selected plan type (default: monthly)
$selectedPlanType = $_GET['plan_type'] ?? 'monthly';
$currentPlans = $plansByType[$selectedPlanType];
?>

<main class="flex-1 max-w-[1200px] mx-auto w-full px-6 py-8">
    <!-- Breadcrumbs -->
    <div class="flex flex-wrap gap-2 py-4">
        <a class="text-[#545095] dark:text-gray-400 text-sm font-medium hover:text-primary"
            href="<?php echo baseUrl('index.php'); ?>">Home</a>
        <span class="text-[#545095] dark:text-gray-400 text-sm font-medium">/</span>
        <a class="text-[#545095] dark:text-gray-400 text-sm font-medium hover:text-primary"
            href="<?php echo baseUrl('index.php'); ?>">Solutions</a>
        <span class="text-[#545095] dark:text-gray-400 text-sm font-medium">/</span>
        <span class="text-[#0f0e1b] dark:text-white text-sm font-medium">
            <?php echo e($product['name']); ?>
        </span>
    </div>

    <!-- Page Heading & Trust Badges -->
    <div class="flex flex-col lg:flex-row justify-between items-start gap-8 mt-4 mb-12">
        <div class="flex flex-col gap-4 max-w-2xl">
            <h1 class="text-[#0f0e1b] dark:text-white text-5xl font-black leading-tight tracking-[-0.033em]">
                <?php echo e($product['name']); ?>
            </h1>
            <p class="text-[#545095] dark:text-gray-400 text-lg font-normal leading-relaxed">
                <?php echo e($product['full_description']); ?>
            </p>
            <div class="flex flex-wrap gap-4 mt-2">
                <div
                    class="flex items-center gap-2 bg-white dark:bg-white/5 border border-[#e8e8f3] dark:border-white/10 px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-primary text-lg">verified</span>
                    <span class="text-sm font-medium">PCI Compliant</span>
                </div>
                <div
                    class="flex items-center gap-2 bg-white dark:bg-white/5 border border-[#e8e8f3] dark:border-white/10 px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-primary text-lg">support_agent</span>
                    <span class="text-sm font-medium">24/7 Support</span>
                </div>
                <div
                    class="flex items-center gap-2 bg-white dark:bg-white/5 border border-[#e8e8f3] dark:border-white/10 px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-primary text-lg">speed</span>
                    <span class="text-sm font-medium">99.9% Uptime</span>
                </div>
            </div>
        </div>
        <div class="flex flex-col items-end gap-4 min-w-[300px]">
            <div class="flex items-center -space-x-4 px-4 py-3">
                <div class="rounded-full size-12 border-4 border-white"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                <div class="rounded-full size-12 border-4 border-white"
                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></div>
                <div class="rounded-full size-12 border-4 border-white"
                    style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
                <div
                    class="bg-primary flex items-center justify-center border-4 border-white rounded-full size-12 text-white text-xs font-bold">
                    5k+</div>
            </div>
            <p class="text-right text-sm text-[#545095] dark:text-gray-400">Trusted by over 5,000 businesses globally
            </p>
        </div>
    </div>

    <!-- Features Grid -->
    <?php if (!empty($features)): ?>
        <div class="mb-20">
            <h2 class="text-[#0f0e1b] dark:text-white text-2xl font-bold mb-8 flex items-center gap-2">
                <span class="w-8 h-1 bg-primary rounded-full"></span>
                Key Features
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($features as $feature): ?>
                    <div
                        class="bg-white dark:bg-white/5 p-6 rounded-xl border border-[#e8e8f3] dark:border-white/10 hover:shadow-lg transition-shadow">
                        <span class="material-symbols-outlined text-primary text-4xl mb-4">
                            <?php echo e($feature['feature_icon']); ?>
                        </span>
                        <h3 class="text-lg font-bold mb-2">
                            <?php echo e($feature['feature_title']); ?>
                        </h3>
                        <p class="text-[#545095] dark:text-gray-400 text-sm">
                            <?php echo e($feature['feature_description']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pricing Section -->
    <div class="mb-20" id="pricing">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black mb-4">Simple, Transparent Pricing</h2>
            <p class="text-[#545095] dark:text-gray-400 max-w-lg mx-auto mb-8">Choose the plan that fits your business
                stage. No hidden fees, cancel anytime.</p>

            <!-- Plan Type Toggle -->
            <div class="inline-flex p-1 bg-[#e8e8f3] dark:bg-white/5 rounded-xl">
                <a href="?slug=<?php echo $slug; ?>&plan_type=monthly"
                    class="px-6 py-2 rounded-lg text-sm font-bold <?php echo $selectedPlanType === 'monthly' ? 'bg-white dark:bg-white/10 shadow-sm' : 'text-[#545095] dark:text-gray-400'; ?>">
                    Monthly
                </a>
                <a href="?slug=<?php echo $slug; ?>&plan_type=semi_annual"
                    class="px-6 py-2 rounded-lg text-sm font-medium <?php echo $selectedPlanType === 'semi_annual' ? 'bg-white dark:bg-white/10 shadow-sm' : 'text-[#545095] dark:text-gray-400'; ?>">
                    6 Months
                </a>
                <a href="?slug=<?php echo $slug; ?>&plan_type=yearly"
                    class="px-6 py-2 rounded-lg text-sm font-medium <?php echo $selectedPlanType === 'yearly' ? 'bg-white dark:bg-white/10 shadow-sm' : 'text-[#545095] dark:text-gray-400'; ?> flex items-center gap-2">
                    1 Year <span
                        class="bg-accent-green text-white text-[10px] px-1.5 py-0.5 rounded-full uppercase">Save
                        25%</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            <?php foreach ($currentPlans as $index => $plan):
                $isPopular = $plan['is_popular'];
                $features = json_decode($plan['features'], true) ?? [];
                ?>
                <div
                    class="<?php echo $isPopular ? 'relative flex flex-col bg-white dark:bg-[#1a1930] p-8 rounded-2xl border-2 border-primary shadow-xl scale-105 z-10' : 'flex flex-col bg-white dark:bg-white/5 p-8 rounded-2xl border border-[#e8e8f3] dark:border-white/10'; ?>">
                    <?php if ($isPopular): ?>
                        <div
                            class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-white text-xs font-bold px-4 py-1 rounded-full uppercase">
                            Best Value</div>
                    <?php endif; ?>

                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-1">
                            <?php echo e($plan['plan_name']); ?>
                        </h3>
                        <p class="text-sm text-[#545095] dark:text-gray-400">For growing businesses</p>
                        <div class="mt-6 flex items-baseline gap-1">
                            <span class="text-4xl font-black">
                                <?php echo formatPrice($plan['price']); ?>
                            </span>
                            <span class="text-sm text-[#545095]">/
                                <?php echo $plan['billing_cycle']; ?> months
                            </span>
                        </div>
                    </div>

                    <ul class="flex-1 space-y-4 mb-8">
                        <?php foreach ($features as $feature): ?>
                            <li class="flex items-start gap-3 text-sm">
                                <span class="material-symbols-outlined text-accent-green">check_circle</span>
                                <?php echo e($feature); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="<?php echo baseUrl('checkout.php?plan_id=' . $plan['id']); ?>"
                        class="w-full py-4 rounded-xl <?php echo $isPopular ? 'bg-primary text-white hover:opacity-90' : 'border-2 border-[#e8e8f3] dark:border-white/10 hover:bg-[#e8e8f3] dark:hover:bg-white/10'; ?> font-bold text-center transition-all">
                        <?php echo $isPopular ? 'Get Started Now' : 'Select Plan'; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sticky CTA Bar -->
    <div
        class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-[90%] max-w-3xl bg-white dark:bg-[#1a1930] border border-[#e8e8f3] dark:border-white/10 shadow-2xl rounded-2xl px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="hidden sm:block">
            <p class="font-bold text-lg">
                <?php echo e($product['name']); ?>
            </p>
            <p class="text-xs text-[#545095] dark:text-gray-400">
                Starting from
                <?php echo formatPrice(min(array_column($pricingPlans, 'price'))); ?>/mo
            </p>
        </div>
        <div class="flex gap-4 w-full sm:w-auto">
            <a href="<?php echo baseUrl('checkout.php?product_id=' . $product['id']); ?>"
                class="flex-1 sm:flex-none px-8 h-12 bg-accent-green text-white font-bold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-xl">shopping_cart</span>
                Buy Now
            </a>
            <a href="<?php echo baseUrl('consultation.php'); ?>"
                class="flex-1 sm:flex-none px-8 h-12 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-xl">event</span>
                Book Consultation
            </a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>