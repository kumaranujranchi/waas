<?php
/**
 * Admin - Products List
 */

$pageTitle = 'Manage Products | Admin';
include __DIR__ . '/../../includes/header.php';

requireAdmin();

require_once __DIR__ . '/../../models/Product.php';

$productModel = new Product();
$products = $productModel->getAllProducts();
?>

<main class="flex-1 bg-background-light dark:bg-background-dark min-h-screen py-8 px-6">
    <div class="max-w-[1400px] mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-2">Manage Products</h1>
                <p class="text-[#545095] dark:text-gray-400">
                    <?php echo count($products); ?> products total
                </p>
            </div>
            <a href="<?php echo baseUrl('admin/products/add.php'); ?>"
                class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-lg">
                <span class="material-symbols-outlined">add</span>
                Add New Product
            </a>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($products as $product): ?>
                <div
                    class="bg-white dark:bg-white/5 rounded-xl border border-[#e8e8f3] dark:border-white/10 overflow-hidden">
                    <div class="aspect-video w-full bg-gray-100 dark:bg-white/10 flex items-center justify-center">
                        <?php if ($product['image_url']): ?>
                            <img src="<?php echo e($product['image_url']); ?>" alt="<?php echo e($product['name']); ?>"
                                class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="material-symbols-outlined text-6xl text-gray-400">image</span>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-[#0f0e1b] dark:text-white mb-1">
                                    <?php echo e($product['name']); ?>
                                </h3>
                                <?php if ($product['badge']): ?>
                                    <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded">
                                        <?php echo e($product['badge']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if ($product['is_featured']): ?>
                                <span
                                    class="text-xs font-bold text-accent-green bg-accent-green/10 px-2 py-1 rounded">Featured</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                            <?php echo e($product['short_description']); ?>
                        </p>
                        <div class="flex gap-2">
                            <a href="<?php echo baseUrl('admin/products/edit.php?id=' . $product['id']); ?>"
                                class="flex-1 text-center px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:opacity-90">
                                Edit
                            </a>
                            <a href="<?php echo baseUrl('product-detail.php?slug=' . $product['slug']); ?>" target="_blank"
                                class="px-4 py-2 border border-gray-300 dark:border-white/10 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-white/5">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="text-center py-16">
                <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">inventory_2</span>
                <p class="text-gray-500 text-lg mb-4">No products yet</p>
                <a href="<?php echo baseUrl('admin/products/add.php'); ?>"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold hover:opacity-90">
                    <span class="material-symbols-outlined">add</span>
                    Add Your First Product
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>