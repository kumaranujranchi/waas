<?php
/**
 * Admin - Edit Product
 */

$pageTitle = 'Edit Product';
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';

$productModel = new Product();
$categoryModel = new Category();

// Get product ID
$productId = $_GET['id'] ?? null;

if (!$productId) {
    setFlashMessage('error', 'Product ID is required');
    redirect(baseUrl('admin/products/list.php'));
}

// Get product details
$product = $productModel->getProductById($productId);

if (!$product) {
    setFlashMessage('error', 'Product not found');
    redirect(baseUrl('admin/products/list.php'));
}

$categories = $categoryModel->getAllCategories();
$pricingPlans = $productModel->getProductPricingPlans($productId);
$faqs = $productModel->getProductFAQs($productId);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'category_id' => $_POST['category_id'] ?? null,
        'name' => sanitizeInput($_POST['name'] ?? ''),
        'slug' => generateSlug($_POST['name'] ?? ''),
        'short_description' => sanitizeInput($_POST['short_description'] ?? ''),
        'full_description' => $_POST['full_description'] ?? '',
        'image_url' => sanitizeInput($_POST['image_url'] ?? ''),
        'badge' => sanitizeInput($_POST['badge'] ?? ''),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'status' => $_POST['status'] ?? 'active'
    ];

    $errors = [];

    if (empty($data['name']))
        $errors[] = 'Product name is required';
    if (empty($data['category_id']))
        $errors[] = 'Category is required';
    if (empty($data['short_description']))
        $errors[] = 'Short description is required';

    if (empty($errors)) {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            // Update product
            $updated = $productModel->updateProduct($productId, $data);

            if ($updated) {
                // Delete existing pricing plans and FAQs
                $db->execute("DELETE FROM pricing_plans WHERE product_id = ?", [$productId]);
                $db->execute("DELETE FROM product_faqs WHERE product_id = ?", [$productId]);

                // Add new pricing plans
                $pricing = [
                    ['name' => 'Monthly', 'type' => 'monthly', 'price' => $_POST['price_monthly'] ?? 0, 'cycle' => 1],
                    ['name' => 'Half-Yearly', 'type' => 'semi_annual', 'price' => $_POST['price_half_yearly'] ?? 0, 'cycle' => 6],
                    ['name' => 'Yearly', 'type' => 'yearly', 'price' => $_POST['price_yearly'] ?? 0, 'cycle' => 12]
                ];

                foreach ($pricing as $plan) {
                    if ($plan['price'] > 0) {
                        $productModel->createPricingPlan([
                            'product_id' => $productId,
                            'plan_name' => $plan['name'],
                            'plan_type' => $plan['type'],
                            'price' => $plan['price'],
                            'billing_cycle' => $plan['cycle'],
                            'status' => 'active'
                        ]);
                    }
                }

                // Add new FAQs
                if (!empty($_POST['faq_question'])) {
                    foreach ($_POST['faq_question'] as $index => $question) {
                        $answer = $_POST['faq_answer'][$index] ?? '';
                        if (!empty($question) && !empty($answer)) {
                            $productModel->createFAQ([
                                'product_id' => $productId,
                                'question' => sanitizeInput($question),
                                'answer' => sanitizeInput($answer),
                                'display_order' => $index
                            ]);
                        }
                    }
                }

                $db->commit();
                setFlashMessage('success', 'Product updated successfully!');
                redirect(baseUrl('admin/products/list.php'));
            } else {
                $db->rollback();
                setFlashMessage('error', 'Failed to update product');
            }
        } catch (Exception $e) {
            if (isset($db))
                $db->rollback();
            setFlashMessage('error', 'Error: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Prepare pricing data
$pricingData = [
    'monthly' => 0,
    'half_yearly' => 0,
    'yearly' => 0
];

foreach ($pricingPlans as $plan) {
    if ($plan['plan_type'] === 'monthly') {
        $pricingData['monthly'] = $plan['price'];
    } elseif ($plan['plan_type'] === 'semi_annual') {
        $pricingData['half_yearly'] = $plan['price'];
    } elseif ($plan['plan_type'] === 'yearly') {
        $pricingData['yearly'] = $plan['price'];
    }
}
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo baseUrl('admin/products/list.php'); ?>"
            class="size-10 rounded-xl bg-white dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-white/10 transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">Edit Product</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Update product details</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="" class="space-y-8">

        <!-- Basic Information -->
        <div class="bg-white dark:bg-white/5 rounded-xl p-8 border-2 border-gray-300 dark:border-white/10 shadow-sm">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                Basic Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Product Name *</label>
                    <input type="text" name="name" required value="<?php echo e($product['name']); ?>"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="e.g., E-commerce Elite">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Category *</label>
                    <select name="category_id" required
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo e($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Badge (Optional)</label>
                    <input type="text" name="badge" value="<?php echo e($product['badge']); ?>"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="e.g., POPULAR, NEW">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Thumbnail Image
                        URL</label>
                    <input type="url" name="image_url" value="<?php echo e($product['image_url']); ?>"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="https://example.com/image.jpg">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Short Description
                        *</label>
                    <textarea name="short_description" required rows="2"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="Brief description (1-2 lines)"><?php echo e($product['short_description']); ?></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Full Description</label>
                    <textarea name="full_description" rows="6"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="Detailed product description"><?php echo e($product['full_description']); ?></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" <?php echo $product['is_featured'] ? 'checked' : ''; ?>
                    class="w-5 h-5 rounded border-2 border-gray-300 text-primary focus:ring-primary">
                    <label for="is_featured" class="text-sm font-bold text-[#0f0e1b] dark:text-white">Mark as
                        Featured</label>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        <option value="active" <?php echo $product['status'] === 'active' ? 'selected' : ''; ?>>Active
                        </option>
                        <option value="inactive" <?php echo $product['status'] === 'inactive' ? 'selected' : ''; ?>
                            >Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pricing Plans -->
        <div class="bg-white dark:bg-white/5 rounded-xl p-8 border-2 border-gray-300 dark:border-white/10 shadow-sm">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">payments</span>
                Pricing Plans
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Monthly Price (₹)</label>
                    <input type="number" name="price_monthly" step="0.01" min="0"
                        value="<?php echo $pricingData['monthly']; ?>"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Half-Yearly Price
                        (₹)</label>
                    <input type="number" name="price_half_yearly" step="0.01" min="0"
                        value="<?php echo $pricingData['half_yearly']; ?>"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Yearly Price (₹)</label>
                    <input type="number" name="price_yearly" step="0.01" min="0"
                        value="<?php echo $pricingData['yearly']; ?>"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                        placeholder="0.00">
                </div>
            </div>
        </div>

        <!-- FAQs -->
        <div class="bg-white dark:bg-white/5 rounded-xl p-8 border-2 border-gray-300 dark:border-white/10 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">help</span>
                    Frequently Asked Questions
                </h2>
                <button type="button" onclick="addFAQ()"
                    class="px-4 py-2 bg-primary text-white rounded-lg font-bold hover:opacity-90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    Add FAQ
                </button>
            </div>
            <div id="faq-container" class="space-y-4">
                <?php if (!empty($faqs)): ?>
                    <?php foreach ($faqs as $index => $faq): ?>
                        <div class="faq-item p-4 border-2 border-gray-300 dark:border-white/10 rounded-lg">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-sm font-bold text-gray-500">FAQ #
                                    <?php echo $index + 1; ?>
                                </span>
                                <button type="button" onclick="this.parentElement.parentElement.remove()"
                                    class="text-red-500 hover:text-red-700">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                            <input type="text" name="faq_question[]" value="<?php echo e($faq['question']); ?>"
                                class="w-full px-4 py-2 mb-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary outline-none"
                                placeholder="Question">
                            <textarea name="faq_answer[]" rows="2"
                                class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary outline-none"
                                placeholder="Answer"><?php echo e($faq['answer']); ?></textarea>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4">
            <button type="submit"
                class="px-8 py-4 bg-primary text-white rounded-xl font-bold hover:opacity-90 shadow-lg shadow-primary/25 transition-all">
                Update Product
            </button>
            <a href="<?php echo baseUrl('admin/products/list.php'); ?>"
                class="px-8 py-4 bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-white rounded-xl font-bold hover:bg-gray-300 dark:hover:bg-white/20 transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    let faqCount = <?php echo count($faqs); ?>;

    function addFAQ() {
        faqCount++;
        const container = document.getElementById('faq-container');
        const faqItem = document.createElement('div');
        faqItem.className = 'faq-item p-4 border-2 border-gray-300 dark:border-white/10 rounded-lg';
        faqItem.innerHTML = `
            <div class="flex justify-between items-start mb-3">
                <span class="text-sm font-bold text-gray-500">FAQ #${faqCount}</span>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
            <input type="text" name="faq_question[]" 
                class="w-full px-4 py-2 mb-3 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary outline-none" 
                placeholder="Question">
            <textarea name="faq_answer[]" rows="2" 
                class="w-full px-4 py-2 rounded-lg border-2 border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary outline-none" 
                placeholder="Answer"></textarea>
        `;
        container.appendChild(faqItem);
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>