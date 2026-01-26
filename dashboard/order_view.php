<?php
/**
 * User - View Order Details with Tracking
 */

$pageTitle = 'Order Tracking';
include __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../models/Order.php';

$orderModel = new Order();
$userId = getCurrentUserId();
$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    redirect(baseUrl('dashboard/orders.php'));
}

$order = $orderModel->getOrderById($orderId);

// Security
if (!$order || $order['user_id'] != $userId) {
    redirect(baseUrl('dashboard/orders.php'));
}

$orderItems = $orderModel->getOrderItems($orderId);

// Status Mapping for Stepper
$statuses = ['pending', 'requirements', 'development', 'deployment', 'completed'];
$currentStatus = $order['order_status'] ?? 'pending';
$currentIndex = array_search($currentStatus, $statuses);
if ($currentIndex === false)
    $currentIndex = 0; // Fallback for cancelled

$stages = [
    ['id' => 'pending', 'label' => 'Order Placed', 'icon' => 'shopping_cart', 'desc' => 'We have received your order.'],
    ['id' => 'requirements', 'label' => 'Requirements', 'icon' => 'assignment', 'desc' => 'Capturing your project needs.'],
    ['id' => 'development', 'label' => 'Development', 'icon' => 'code', 'desc' => 'Building your custom website.'],
    ['id' => 'deployment', 'label' => 'Deployment', 'icon' => 'rocket_launch', 'desc' => 'Hosting and final testing.'],
    ['id' => 'completed', 'label' => 'Live', 'icon' => 'check_circle', 'desc' => 'Your website is now live!']
];
?>

<div class="mb-8">
    <div class="flex items-center gap-4 mb-4">
        <a href="<?php echo baseUrl('dashboard/orders.php'); ?>"
            class="size-10 rounded-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-center text-gray-500 hover:text-primary transition-all">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-[#0f0e1b] dark:text-white">Track Order #
                <?php echo e(substr($order['order_number'], -8)); ?>
            </h2>
            <p class="text-gray-600 dark:text-gray-400">Real-time progress of your project</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Stepper Column -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-[#1e1d2d] rounded-3xl border border-gray-200 dark:border-white/5 p-8 shadow-sm">
            <h3 class="text-lg font-black text-[#0f0e1b] dark:text-white mb-10 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">timeline</span>
                Project Timeline
            </h3>

            <!-- Vertical Stepper -->
            <div class="relative space-y-12 ml-4">
                <!-- Progress Line -->
                <div class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-gray-100 dark:bg-white/5"></div>

                <?php foreach ($stages as $index => $stage):
                    $isCompleted = $index < $currentIndex;
                    $isCurrent = $index === $currentIndex;
                    $isPending = $index > $currentIndex;

                    $bgColor = $isCompleted ? 'bg-accent-green' : ($isCurrent ? 'bg-primary animate-pulse' : 'bg-gray-100 dark:bg-white/10');
                    $textColor = $isPending ? 'text-gray-400 opacity-50' : 'text-[#0f0e1b] dark:text-white';
                    $iconColor = $isCompleted ? 'text-white' : ($isCurrent ? 'text-white' : 'text-gray-400');
                    ?>
                    <div class="relative flex items-start gap-6 group">
                        <!-- Dot/Icon -->
                        <div
                            class="relative z-10 size-10 rounded-full <?php echo $bgColor; ?> flex items-center justify-center shadow-lg transition-transform group-hover:scale-110 duration-300">
                            <span class="material-symbols-outlined text-xl <?php echo $iconColor; ?>">
                                <?php echo $isCompleted ? 'check' : $stage['icon']; ?>
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="<?php echo $textColor; ?>">
                            <h4 class="text-lg font-black leading-tight">
                                <?php echo $stage['label']; ?>
                            </h4>
                            <p
                                class="text-sm font-medium mt-1 <?php echo $isPending ? 'text-gray-400' : 'text-gray-500 dark:text-gray-400'; ?>">
                                <?php echo $stage['desc']; ?>
                            </p>
                            <?php if ($isCurrent): ?>
                                <span
                                    class="inline-flex mt-3 px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full">Current
                                    Stage</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Order Summary Column -->
    <div class="space-y-6">
        <div class="bg-white dark:bg-[#1e1d2d] rounded-2xl border border-gray-200 dark:border-white/5 p-6 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-400 mb-6">Service Summary</h3>

            <div class="space-y-4">
                <?php foreach ($orderItems as $item): ?>
                    <div
                        class="flex justify-between items-start pt-4 first:pt-0 border-t border-gray-100 dark:border-white/5 first:border-0">
                        <div>
                            <p class="font-bold text-[#0f0e1b] dark:text-white">
                                <?php echo e($item['product_name']); ?>
                            </p>
                            <p class="text-xs text-gray-500">
                                <?php echo e($item['plan_name']); ?> Plan
                            </p>
                        </div>
                        <span class="font-black text-primary">
                            <?php echo formatPrice($item['price']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-8 pt-6 border-t-2 border-dashed border-gray-200 dark:border-white/10 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-bold">Total Amount</span>
                    <span class="text-xl font-black text-[#0f0e1b] dark:text-white">
                        <?php echo formatPrice($order['final_amount']); ?>
                    </span>
                </div>
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-gray-400 uppercase tracking-widest">Payment Status</span>
                    <span class="text-accent-green uppercase">
                        <?php echo $order['payment_status']; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-primary/5 rounded-2xl p-6 border border-primary/10">
            <h4 class="text-xs font-black uppercase tracking-widest text-primary mb-4">Helpful Links</h4>
            <div class="grid grid-cols-1 gap-2">
                <a href="<?php echo baseUrl('dashboard/invoice.php?order_id=' . $order['id']); ?>" target="_blank"
                    class="flex items-center gap-3 p-3 bg-white dark:bg-white/5 rounded-xl text-sm font-bold text-[#0f0e1b] dark:text-white hover:bg-white/50 transition-all border border-gray-100 dark:border-white/5">
                    <span class="material-symbols-outlined text-primary">download</span>
                    Download Invoice
                </a>
                <a href="#"
                    class="flex items-center gap-3 p-3 bg-white dark:bg-white/5 rounded-xl text-sm font-bold text-[#0f0e1b] dark:text-white hover:bg-white/50 transition-all border border-gray-100 dark:border-white/5">
                    <span class="material-symbols-outlined text-primary">support_agent</span>
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>