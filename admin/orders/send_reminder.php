<?php
/**
 * Send Payment Reminder Handler
 */

require_once __DIR__ . '/../includes/header.php'; // Includes config, db, auth check
// requireAdmin() check should be in header or explicitly called if header doesn't enforce it universally for /admin/
// Assuming header checks login, but adding explicit check safely.

if (!isAdmin()) {
    setFlashMessage('error', 'Access Denied');
    redirect(baseUrl('admin/index.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;

    if (!$orderId) {
        setFlashMessage('error', 'Invalid Order ID');
        redirect(baseUrl('admin/orders/list.php'));
    }

    require_once __DIR__ . '/../../includes/functions.php';

    if (sendPaymentPendingEmail($orderId)) {
        setFlashMessage('success', 'Payment reminder sent successfully!');
    } else {
        setFlashMessage('error', 'Failed to send reminder. Check logs.');
    }

    redirect(baseUrl('admin/orders/view.php?id=' . $orderId));
} else {
    redirect(baseUrl('admin/orders/list.php'));
}
