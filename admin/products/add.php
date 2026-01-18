<?php
/**
 * Admin - Add New Product
 */

$pageTitle = 'Add Product | Admin';
include __DIR__ . '/../../includes/header.php';

requireAdmin();

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
        'full_description' => sanitizeInput($_POST['full_description'] ?? ''),
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
        $productId = $productModel->createProduct($data);

        if ($productId) {
            setFlashMessage('success', 'Product created successfully!');
            redirect(baseUrl('admin/products/list.php'));
        } else {
            setFlashMessage('error', 'Failed to create product');
        }
    } else {
        setFlashMessage('error', implode('<br>', $errors));
    }
}
?>

<main class="flex-1 bg-background-light dark:bg-background-dark min-h-screen py-8 px-6">
    <div class="max-w-[900px] mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="<?php echo baseUrl('admin/products/list.php'); ?>"
                class="w-10 h-10 rounded-lg bg-white dark:bg-white/5 border border-[#e8e8f3] dark:border-white/10 flex items-center justify-center hover:bg-gray-50 dark:hover:bg-white/10">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white">Add New Product</h1>
                <p class="text-[#545095] dark:text-gray-400">Create a new product listing</p>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action=""
            class="bg-white dark:bg-white/5 rounded-xl p-8 border border-[#e8e8f3] dark:border-white/10">
            <div class="space-y-6">
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Product Name *</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                        placeholder="E-commerce Elite" />
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Category *</label>
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

                <!-- Short Description -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Short Description
                        *</label>
                    <textarea name="short_description" required rows="2"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                        placeholder="Brief description for product card"></textarea>
                </div>

                <!-- Full Description -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Full Description</label>
                    <textarea name="full_description" rows="4"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                        placeholder="Detailed product description"></textarea>
                </div>

                <!-- Image URL -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Image URL</label>
                    <input type="url" name="image_url"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                        placeholder="https://via.placeholder.com/400x300" />
                    <p class="text-xs text-gray-500 mt-1">Use placeholder:
                        https://via.placeholder.com/400x300/14b8a6/ffffff?text=Product</p>
                </div>

                <!-- Badge -->
                <div>
                    <label class="block text-sm font-bold text-[#0f0e1b] dark:text-white mb-2">Badge (Optional)</label>
                    <input type="text" name="badge"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-white/10 dark:bg-white/5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                        placeholder="Website, Software, etc." />
                </div>

                <!-- Featured -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_featured" id="is_featured"
                        class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary" />
                    <label for="is_featured" class="text-sm font-medium text-[#0f0e1b] dark:text-white">
                        Mark as Featured Product
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-white/10">
                <button type="submit"
                    class="flex-1 py-4 bg-primary text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-lg">
                    Create Product
                </button>
                <a href="<?php echo baseUrl('admin/products/list.php'); ?>"
                    class="px-8 py-4 border-2 border-gray-300 dark:border-white/10 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>