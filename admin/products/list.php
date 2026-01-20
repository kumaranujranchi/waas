<?php
/**
 * Admin - Products List
 */

$pageTitle = 'Manage Services';
include __DIR__ . '/../includes/header.php';

require_once __DIR__ . '/../../models/Product.php';

$productModel = new Product();
$products = $productModel->getAllProducts();
?>

<div class="p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#0f0e1b] dark:text-white mb-1">Manage Services</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                <?php echo count($products); ?> Products Available
            </p>
        </div>
        <a href="<?php echo baseUrl('admin/products/add.php'); ?>"
            class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined">add</span>
            Add New Solution
        </a>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($products as $product): ?>
            <div
                class="bg-white dark:bg-white/5 rounded-2xl border-2 border-gray-300 dark:border-white/10 overflow-hidden hover:shadow-xl transition-all group shadow-sm">
                <div
                    class="aspect-video w-full bg-gray-100 dark:bg-white/10 flex items-center justify-center relative overflow-hidden">
                    <?php
                    $imageUrl = $product['image_url'];
                    if (!empty($imageUrl)) {
                        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                            $imageUrl = baseUrl($imageUrl);
                        }
                    }
                    ?>
                    <?php if ($imageUrl): ?>
                        <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($product['name']); ?>"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                        <span class="material-symbols-outlined text-6xl text-gray-300">image</span>
                    <?php endif; ?>
                    <div class="absolute top-4 left-4 flex gap-2">
                        <?php if ($product['badge']): ?>
                            <span
                                class="text-[10px] font-black text-white bg-primary px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">
                                <?php echo e($product['badge']); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($product['is_featured']): ?>
                            <span
                                class="text-[10px] font-black text-white bg-accent-green px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Featured</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-black text-[#0f0e1b] dark:text-white mb-2 line-clamp-1">
                        <?php echo e($product['name']); ?>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 line-clamp-2 h-10">
                        <?php echo e($product['short_description']); ?>
                    </p>
                    <div class="flex gap-2">
                        <a href="<?php echo baseUrl('admin/products/edit.php?id=' . $product['id']); ?>"
                            class="flex-1 text-center px-4 py-3 bg-gray-50 dark:bg-white/5 border-2 border-gray-300 dark:border-white/10 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary hover:text-white hover:border-primary transition-all">
                            Edit
                        </a>
                        <a href="<?php echo baseUrl('product-detail.php?slug=' . $product['slug']); ?>" target="_blank"
                            class="size-11 flex items-center justify-center border-2 border-gray-300 dark:border-white/10 rounded-xl text-gray-400 hover:text-primary transition-all">
                            <span class="material-symbols-outlined">visibility</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($products)): ?>
        <div
            class="text-center py-24 bg-white dark:bg-white/5 border border-dashed border-gray-200 dark:border-white/10 rounded-2xl">
            <span class="material-symbols-outlined text-7xl text-gray-200 mb-4">inventory_2</span>
            <p class="text-gray-400 font-black uppercase tracking-widest mb-6">No solutions found</p>
            <a href="<?php echo baseUrl('admin/products/add.php'); ?>"
                class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white rounded-xl font-black uppercase tracking-widest hover:opacity-90 shadow-lg shadow-primary/20 transition-all">
                <span class="material-symbols-outlined">add</span>
                Create First Solution
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>