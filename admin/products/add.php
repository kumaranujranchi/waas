<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requireAdmin();

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
        'full_description' => $_POST['full_description'] ?? '', // Allow HTML
        'badge' => sanitizeInput($_POST['badge'] ?? ''),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'status' => 'active'
    ];

    $image_url = '';

    // Handle File Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            $uploadDir = __DIR__ . '/../../uploads/products/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('prod_') . '.' . $fileExtension;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image_url = 'uploads/products/' . $fileName;
            } else {
                setFlashMessage('error', 'Failed to upload image.');
            }
        } else {
            setFlashMessage('error', 'Invalid file type. Only JPG, PNG, WEBP, and GIF are allowed.');
        }
    }

    // Assign image URL to data (either uploaded or empty)
    $data['image_url'] = $image_url;

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
                if (isset($_POST['faq_question']) && is_array($_POST['faq_question'])) {
                    // Filter out empty questions
                    $questions = array_values(array_filter($_POST['faq_question'], function ($q) {
                        return !empty(trim($q)); }));
                    $answers = array_values(array_filter($_POST['faq_answer'], function ($a) {
                        return !empty(trim($a)); }));

                    // Iterate based on the count of questions to avoid index mismatch
                    $totalFaqs = count($questions);

                    for ($i = 0; $i < $totalFaqs; $i++) {
                        $question = $questions[$i];
                        $answer = $answers[$i] ?? ''; // Safely get corresponding answer

                        if (!empty($question) && !empty($answer)) {
                            $productModel->createFAQ([
                                'product_id' => $productId,
                                'question' => sanitizeInput($question),
                                'answer' => sanitizeInput($answer),
                                'display_order' => $i
                            ]);
                        }
                    }
                }


                $db->commit();
                setFlashMessage('success', 'Product created successfully with pricing and FAQs!');
                redirect(baseUrl('admin/products/list.php'));
            } else {
                $db->rollback();
                setFlashMessage('error', 'Failed to save product to database.');
            }
        } catch (Exception $e) {
            $db->rollback();
            setFlashMessage('error', 'Error: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

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

    <!-- Form -->
    <form method="POST" action="" enctype="multipart/form-data" class="space-y-8">


        <!-- Basic Information -->
        <div class="bg-white dark:bg-white/5 rounded-xl p-8 border border-[#e8e8f3] dark:border-white/10 shadow-sm">
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
                        (Optional)</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                    <p class="text-xs text-gray-500 mt-1">Leave empty to use default icon. Supported formats: JPG, PNG,
                        WEBP, GIF</p>
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
                    <div id="editor-container" class="h-[300px] bg-white dark:bg-black/5 rounded-lg mb-4"></div>
                    <input type="hidden" name="full_description" id="full_description">
                </div>

                <div
                    class="md:col-span-2 flex items-center gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-white/5">
                    <input type="checkbox" name="is_featured" id="is_featured"
                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary" />
                    <label for="is_featured" class="text-sm font-medium text-[#0f0e1b] dark:text-white">
                        Mark as Featured Product
                    </label>
                </div>
            </div>
        </div>
</div>

<!-- TinyMCE Integration -->
<!-- Quill Editor Integration -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Detailed explanation of the service...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Update hidden input on change
    quill.on('text-change', function () {
        document.getElementById('full_description').value = quill.root.innerHTML;
    });
</script>




<!-- Pricing Plans -->
<div class="bg-white dark:bg-white/5 rounded-xl p-8 border border-[#e8e8f3] dark:border-white/10 shadow-sm">
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
<div class="bg-white dark:bg-white/5 rounded-xl p-8 border border-[#e8e8f3] dark:border-white/10 shadow-sm">
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
<div class="flex gap-4 pt-4 mb-12">
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