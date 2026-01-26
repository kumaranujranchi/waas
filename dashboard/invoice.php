<?php
/**
 * Invoice Page
 * Printable payment receipt for orders
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Order.php';

// Require login
requireLogin();

$orderId = $_GET['order_id'] ?? null;
$orderModel = new Order();
$userId = getCurrentUserId();

if (!$orderId) {
    die("Order ID required");
}

$order = $orderModel->getOrderById($orderId);
$orderItems = $orderModel->getOrderItems($orderId);

// Security: Prevent users from seeing other users' invoices
if (!$order || $order['user_id'] != $userId) {
    die("Access denied or order not found.");
}

$pageTitle = 'Invoice ' . $order['order_number'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice -
        <?php echo e($order['order_number']); ?>
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .invoice-container {
                border: none !important;
                shadow: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 py-12 px-4">

    <!-- Top Navigation (No Print) -->
    <div class="max-w-[800px] mx-auto mb-6 flex justify-between items-center no-print">
        <a href="<?php echo baseUrl('dashboard/billing.php'); ?>"
            class="flex items-center gap-2 text-gray-600 hover:text-primary font-bold transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Billing
        </a>
        <button onclick="window.print()"
            class="px-6 py-2 bg-primary text-white rounded-lg font-bold shadow-lg hover:shadow-primary/30 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">print</span>
            Print Invoice
        </button>
    </div>

    <!-- Invoice Container -->
    <div
        class="max-w-[800px] mx-auto bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200 invoice-container">
        <!-- Header -->
        <div class="p-8 md:p-12 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between gap-8">
            <div>
                <h1 class="text-3xl font-black text-primary mb-2">
                    <?php echo e(SITE_NAME); ?>
                </h1>
                <p class="text-gray-500 font-medium">
                    <?php echo e(SITE_TAGLINE); ?>
                </p>
                <div class="mt-6 text-sm text-gray-600 space-y-1">
                    <p>contact@siteonsub.com</p>
                    <p>New Delhi, India</p>
                    <p>GSTIN: 07AAAAA0000A1Z5 (Sample)</p>
                </div>
            </div>
            <div class="text-md-right">
                <h2 class="text-5xl font-black text-gray-200 uppercase tracking-tighter mb-4">Invoice</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex md:justify-end gap-3">
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Invoice #</span>
                        <span class="font-black text-gray-900">
                            <?php echo e($order['order_number']); ?>
                        </span>
                    </div>
                    <div class="flex md:justify-end gap-3">
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Date</span>
                        <span class="font-bold text-gray-900">
                            <?php echo formatDate($order['created_at']); ?>
                        </span>
                    </div>
                    <div class="flex md:justify-end gap-3">
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Status</span>
                        <span
                            class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full">Paid</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <!-- Billing Info -->
            <div class="grid grid-cols-2 gap-8 mb-12">
                <div>
                    <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-3">Bill To</h4>
                    <p class="text-lg font-black text-gray-900">
                        <?php echo e($order['billing_name'] ?: $order['user_name']); ?>
                    </p>
                    <p class="text-gray-600 font-medium">
                        <?php echo e($order['billing_email'] ?: $order['user_email']); ?>
                    </p>
                </div>
            </div>

            <!-- Items Table -->
            <table class="w-full mb-12">
                <thead>
                    <tr class="border-b-2 border-gray-900">
                        <th class="py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-400">
                            Description</th>
                        <th class="py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-400">Qty
                        </th>
                        <th class="py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-400">Unit
                            Price</th>
                        <th class="py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-400">
                            Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($orderItems)): ?>
                        <tr>
                            <td class="py-6 font-bold text-gray-900">Subscription -
                                <?php echo e($order['order_number']); ?>
                            </td>
                            <td class="py-6 text-right font-medium">1</td>
                            <td class="py-6 text-right font-medium">
                                <?php echo formatPrice($order['total_amount']); ?>
                            </td>
                            <td class="py-6 text-right font-black">
                                <?php echo formatPrice($order['total_amount']); ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td class="py-6">
                                    <p class="font-bold text-gray-900">
                                        <?php echo e($item['product_name']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500 font-medium">
                                        <?php echo e($item['plan_name']); ?> Plan
                                    </p>
                                </td>
                                <td class="py-6 text-right font-medium text-gray-600">
                                    <?php echo e($item['quantity']); ?>
                                </td>
                                <td class="py-6 text-right font-medium text-gray-600">
                                    <?php echo formatPrice($item['price']); ?>
                                </td>
                                <td class="py-6 text-right font-black text-gray-900">
                                    <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Calculation -->
            <div class="flex justify-end">
                <div class="w-full md:w-1/3 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Subtotal</span>
                        <span class="font-bold text-gray-900">
                            <?php echo formatPrice($order['total_amount']); ?>
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">GST (18%)</span>
                        <span class="font-bold text-gray-900">
                            <?php echo formatPrice($order['tax_amount']); ?>
                        </span>
                    </div>
                    <div class="flex justify-between pt-4 border-t-2 border-gray-900">
                        <span class="text-lg font-black text-gray-900">Total</span>
                        <span class="text-2xl font-black text-primary">
                            <?php echo formatPrice($order['final_amount']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="mt-20 pt-8 border-t border-gray-100">
                <p class="text-[10px] text-center text-gray-400 font-medium tracking-wide">
                    This is a computer-generated invoice and does not require a physical signature.
                    <br>
                    Website: siteonsub.com | Support: info@siteonsub.com
                </p>
            </div>
        </div>
    </div>

    <div class="text-center mt-8 no-print">
        <p class="text-gray-400 text-sm font-medium">Thank you for choosing SiteOnSub!</p>
    </div>

</body>

</html>