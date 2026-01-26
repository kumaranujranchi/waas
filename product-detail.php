<?php
/**
 * Product Detail Page - Dynamic
 */

// Include header
$pageTitle = 'Product Details | SiteOnSub';
include __DIR__ . '/includes/header.php';
?>
<style>
    .rich-text-content h1 {
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 0.5em;
        margin-top: 1em;
    }

    .rich-text-content h2 {
        font-size: 1.5em;
        font-weight: bold;
        margin-bottom: 0.5em;
        margin-top: 1em;
    }

    .rich-text-content h3 {
        font-size: 1.25em;
        font-weight: bold;
        margin-bottom: 0.5em;
        margin-top: 1em;
    }

    .rich-text-content p {
        margin-bottom: 1em;
        line-height: 1.6;
    }

    .rich-text-content ul {
        list-style-type: disc;
        padding-left: 1.5em;
        margin-bottom: 1em;
    }

    .rich-text-content ol {
        list-style-type: decimal;
        padding-left: 1.5em;
        margin-bottom: 1em;
    }

    .rich-text-content li {
        margin-bottom: 0.5em;
    }

    .rich-text-content a {
        color: #3b82f6;
        text-decoration: underline;
    }

    .rich-text-content blockquote {
        border-left: 4px solid #e5e7eb;
        padding-left: 1em;
        font-style: italic;
        color: #6b7280;
        margin-bottom: 1em;
    }

    .rich-text-content strong {
        font-weight: bold;
    }

    .rich-text-content em {
        font-style: italic;
    }
</style>
<?php

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
$pricingPlans = $productModel->getProductPricingPlans($product['id'], true);
// Decode FAQs from JSON column (New Method)
$faqs = !empty($product['faqs']) ? json_decode($product['faqs'], true) : [];
if (!is_array($faqs)) {
    $faqs = [];
}
?>

<main class="flex-1 max-w-[1200px] mx-auto w-full px-6 py-12">
    <!-- Hero Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
        <div class="space-y-8">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <a href="<?php echo baseUrl('index.php'); ?>" class="hover:text-primary transition-colors">Home</a>
                <span class="text-gray-300">/</span>
                <span class="text-[#0f0e1b] dark:text-white"><?php echo e($product['name']); ?></span>
            </div>

            <div>
                <h1
                    class="text-5xl lg:text-6xl font-black text-[#0f0e1b] dark:text-white leading-[1.1] tracking-tight mb-6">
                    <?php echo e($product['name']); ?>
                </h1>
                <div class="rich-text-content text-lg text-gray-500 dark:text-gray-400 leading-relaxed max-w-xl">
                    <?php echo html_entity_decode($product['full_description']); ?>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <div
                    class="flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-white/5 rounded-full border border-gray-100 dark:border-white/10">
                    <span class="material-symbols-outlined text-green-500 text-xl">verified_user</span>
                    <span class="font-bold text-sm text-[#0f0e1b] dark:text-white">Enterprise Ready</span>
                </div>
                <div
                    class="flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-white/5 rounded-full border border-gray-100 dark:border-white/10">
                    <span class="material-symbols-outlined text-blue-500 text-xl">rocket_launch</span>
                    <span class="font-bold text-sm text-[#0f0e1b] dark:text-white">Instant Deployment</span>
                </div>
            </div>

            <div class="flex items-center gap-6 pt-4">
                <a href="#pricing"
                    class="px-8 py-4 bg-primary text-white rounded-2xl font-black text-lg shadow-xl shadow-primary/25 hover:translate-y-[-2px] hover:shadow-2xl hover:shadow-primary/40 transition-all duration-300">
                    View Pricing
                </a>
                <div class="flex items-center gap-3">
                    <div class="flex -space-x-4">
                        <div class="size-10 rounded-full border-2 border-white bg-gray-200"></div>
                        <div class="size-10 rounded-full border-2 border-white bg-gray-300"></div>
                        <div class="size-10 rounded-full border-2 border-white bg-gray-400"></div>
                    </div>
                    <div class="text-sm">
                        <p class="font-bold text-[#0f0e1b] dark:text-white">5,000+ businesses</p>
                        <p class="text-gray-400">trust this solution</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Visual -->
        <div class="relative">
            <div class="absolute inset-0 bg-primary/20 blur-[100px] rounded-full"></div>
            <div
                class="relative bg-white dark:bg-[#1a1c2e] rounded-[2.5rem] p-4 shadow-2xl border border-gray-100 dark:border-white/10 transition-transform duration-500">
                <div class="aspect-[4/3] rounded-[2rem] overflow-hidden bg-gray-50 dark:bg-white/5 relative group">
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
                            class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-300 text-8xl">image</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <?php if (!empty($features)): ?>
        <div class="mb-32">
            <div class="flex items-center gap-4 mb-16">
                <div class="h-px flex-1 bg-gray-100 dark:bg-white/10"></div>
                <h2 class="text-2xl font-black text-[#0f0e1b] dark:text-white uppercase tracking-widest">Everything Included
                </h2>
                <div class="h-px flex-1 bg-gray-100 dark:bg-white/10"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($features as $feature): ?>
                    <div
                        class="group p-8 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:border-primary/20 hover:bg-primary/5 transition-all duration-300">
                        <div
                            class="size-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <span class="material-symbols-outlined text-3xl">
                                <?php echo e($feature['feature_icon'] ?: 'star'); ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-[#0f0e1b] dark:text-white mb-3">
                            <?php echo e($feature['feature_title']); ?>
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 leading-relaxed">
                            <?php echo e($feature['feature_description']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pricing Section -->
    <div id="pricing" class="mb-20 scroll-mt-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl text-[#0f0e1b] dark:text-white font-black mb-6">Transparent Pricing</h2>
            <p class="text-[#545095] dark:text-gray-400 max-w-lg mx-auto">Choose the plan that fits your business
                stage. No hidden fees, cancel anytime.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-8 max-w-6xl mx-auto items-start">
            <?php foreach ($pricingPlans as $index => $plan):
                $isPopular = $plan['is_popular'] ?? false;
                $features = !empty($plan['features']) ? json_decode($plan['features'], true) : [];
                $features = is_array($features) ? $features : [];
                ?>
                <div
                    class="relative flex flex-col p-8 rounded-[2rem] w-full md:w-[380px] transition-all duration-300 <?php echo $isPopular ? 'bg-[#0f0e1b] dark:bg-white text-white dark:text-[#0f0e1b] shadow-2xl scale-105 z-10' : 'bg-white dark:bg-[#1a1c2e] border border-gray-100 dark:border-white/5 text-[#0f0e1b] dark:text-white hover:shadow-xl'; ?>">

                    <?php if ($isPopular): ?>
                        <div
                            class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                            Most Popular
                        </div>
                    <?php endif; ?>

                    <div class="mb-8">
                        <h3 class="text-2xl font-black mb-2"><?php echo e($plan['plan_name']); ?></h3>
                        <div class="flex items-baseline gap-1 my-6">
                            <span class="text-4xl font-black tracking-tight">
                                <?php echo formatPrice($plan['price']); ?>
                            </span>
                            <span class="text-sm font-bold opacity-60">/<?php echo $plan['billing_cycle']; ?>mo</span>
                        </div>
                        <p class="text-sm opacity-60 leading-relaxed">Perfect for growing businesses that need a complete
                            solution.</p>
                    </div>

                    <div class="flex-1 mb-8">
                        <ul class="space-y-4">
                            <?php foreach ($features as $feature): ?>
                                <li class="flex items-start gap-3 text-sm font-medium opacity-80">
                                    <span
                                        class="material-symbols-outlined text-[20px] <?php echo $isPopular ? 'text-green-400 dark:text-green-600' : 'text-green-500'; ?> shrink-0">check_circle</span>
                                    <?php echo e($feature); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <a href="<?php echo baseUrl('checkout.php?plan_id=' . $plan['id']); ?>"
                        class="w-full py-4 rounded-xl font-bold text-center transition-all duration-300 <?php echo $isPopular ? 'bg-white text-black hover:bg-gray-100 dark:bg-black dark:text-white' : 'bg-black text-white hover:bg-gray-800 dark:bg-white dark:text-black'; ?>">
                        Choose Plan
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- FAQ Section -->
    <?php if (!empty($faqs)): ?>
        <div class="mb-32 max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-4">Frequently Asked Questions</h2>
                <p class="text-gray-500 dark:text-gray-400">Everything you need to know about this solution.</p>
            </div>

            <div class="space-y-4">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 overflow-hidden">
                        <button onclick="toggleFaq('faq-<?php echo $index; ?>')"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <span
                                class="font-bold text-lg text-[#0f0e1b] dark:text-white"><?php echo e($faq['question']); ?></span>
                            <span id="icon-faq-<?php echo $index; ?>"
                                class="material-symbols-outlined text-gray-400 transition-transform duration-300">expand_more</span>
                        </button>
                        <div id="faq-<?php echo $index; ?>" class="hidden px-6 pb-6 pt-0">
                            <p
                                class="text-gray-500 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-white/10 pt-4">
                                <?php echo nl2br(e($faq['answer'])); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <script>
                function toggleFaq(id) {
                    const content = document.getElementById(id);
                    const icon = document.getElementById('icon-' + id);
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        content.classList.add('hidden');
                        icon.style.transform = 'rotate(0deg)';
                    }
                }
            </script>
        </div>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>