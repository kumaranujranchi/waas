$pageTitle = 'Add New Solution';
include __DIR__ . '/../includes/header.php';
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo baseUrl('admin/products/list.php'); ?>"
            class="size-10 rounded-xl bg-white dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-white/10 transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">Add New Solution</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Create a comprehensive product listing
            </p>
        </div>
    </div>

<?php
    require_once __DIR__ . '/../../models/Product.php';
    require_once __DIR__ . '/../../models/Category.php';

    $productModel = new Product();
    $categoryModel = new Category();

    $categories = $categoryModel->getAllCategories();

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'category_id' => $_POST['category_id'] ?? null,
            'name' => sanitizeInput($_POST['name'] ?? ''),
            'slug' => generateSlug($_POST['name'] ?? ''),
            'short_description' => sanitizeInput($_POST['short_description'] ?? ''),
            'full_description' => $_POST['full_description'] ?? '', // Allow HTML if needed, but sanitize later if needed
            'image_url' => sanitizeInput($_POST['image_url'] ?? ''),
            'badge' => sanitizeInput($_POST['badge'] ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'status' => 'active'
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
                // Start Transaction via Database instance
                $db = Database::getInstance();
                $db->beginTransaction();

                $productId = $productModel->createProduct($data);

                if ($productId) {
                    // Handle Pricing Plans
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

                    // Handle FAQs
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
                    setFlashMessage('success', 'Product created successfully with pricing and FAQs!');
                    redirect(baseUrl('admin/products/list.php'));
                } else {
                    $db->rollback();
                    setFlashMessage('error', 'Failed to create product base data');
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
    ?>

    <!-- Form -->
    <form method="POST" action="" class="space-y-8">


                <!-- Basic Information -->
                <div
                    class="bg-white dark:bg-white/5 rounded-xl p-8 border border-[#e8e8f3] dark:border-white/10 shadow-sm">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">info</span>
                        Basic Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Product Name
                                *</label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="e.g. E-commerce Elite" />
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Category
                                *</label>
                            <select name="category_id" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo e($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Badge
                                (Optional)</label>
                            <input type="text" name="badge"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="e.g. Website, Software" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Thumbnail Image
                                URL</label>
                            <input type="url" name="image_url"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="https://example.com/image.jpg" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Short Description
                                *</label>
                            <textarea name="short_description" required rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="Brief description for the product card"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Long
                                Description</label>
                            <textarea name="full_description" rows="6"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="Detailed explanation of the service..."></textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_featured" id="is_featured"
                                class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary" />
                            <label for="is_featured" class="text-sm font-medium text-[#0f0e1b] dark:text-white">
                                Mark as Featured Product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Pricing Plans -->
                <div
                    class="bg-white dark:bg-white/5 rounded-xl p-8 border border-[#e8e8f3] dark:border-white/10 shadow-sm">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-accent-green">payments</span>
                        Pricing Plans
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Monthly Price
                                ($)</label>
                            <input type="number" step="0.01" name="price_monthly"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="0.00" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Half-Yearly Price
                                ($)</label>
                            <input type="number" step="0.01" name="price_half_yearly"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="0.00" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Yearly Price
                                ($)</label>
                            <input type="number" step="0.01" name="price_yearly"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="0.00" />
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div
                    class="bg-white dark:bg-white/5 rounded-xl p-8 border border-[#e8e8f3] dark:border-white/10 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-600">quiz</span>
                            Frequently Asked Questions
                        </h2>
                        <button type="button" id="add-faq"
                            class="flex items-center gap-1 text-sm font-bold text-primary hover:text-primary/80 transition-colors">
                            <span class="material-symbols-outlined text-lg">add_circle</span>
                            Add FAQ
                        </button>
                    </div>

                    <div id="faq-container" class="space-y-4">
                        <div
                            class="faq-item p-4 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-200 dark:border-white/10 relative">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Question</label>
                                    <input type="text" name="faq_question[]"
                                        class="w-full px-3 py-2 rounded border border-gray-300 dark:border-white/10 dark:bg-white/5 outline-none"
                                        placeholder="e.g. What is the turnaround time?" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Answer</label>
                                    <textarea name="faq_answer[]" rows="2"
                                        class="w-full px-3 py-2 rounded border border-gray-300 dark:border-white/10 dark:bg-white/5 outline-none"
                                        placeholder="e.g. Usually 2-3 business days..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 py-4 bg-primary text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-lg text-lg">
                        Create Product Listing
                    </button>
                    <a href="<?php echo baseUrl('admin/products/list.php'); ?>"
                        class="px-10 py-4 border-2 border-gray-300 dark:border-white/10 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-white/5 transition-all text-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('add-faq').addEventListener('click', function () {
            const container = document.getElementById('faq-container');
            const newItem = document.createElement('div');
            newItem.className = 'faq-item p-4 bg-gray-50 dark:bg-white/5 rounded-lg border border-gray-200 dark:border-white/10 relative';
            newItem.innerHTML = `
        <button type="button" class="remove-faq absolute top-2 right-2 text-gray-400 hover:text-red-500">
            <span class="material-symbols-outlined">cancel</span>
        </button>
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Question</label>
                <input type="text" name="faq_question[]" 
                    class="w-full px-3 py-2 rounded border border-gray-300 dark:border-white/10 dark:bg-white/5 outline-none"
                    placeholder="e.g. Question here..." />
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Answer</label>
                <textarea name="faq_answer[]" rows="2"
                    class="w-full px-3 py-2 rounded border border-gray-300 dark:border-white/10 dark:bg-white/5 outline-none"
                    placeholder="e.g. Answer here..."></textarea>
            </div>
        </div>
    `;
            container.appendChild(newItem);

            // Add remove functionality
            newItem.querySelector('.remove-faq').addEventListener('click', function () {
                newItem.remove();
            });
        });
    </script>

    <?php include __DIR__ . '/../includes/footer.php'; ?>