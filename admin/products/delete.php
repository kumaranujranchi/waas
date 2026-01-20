<?php
/**
 * Delete Product
 */

session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../models/Product.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect(baseUrl('auth/login.php'));
}

$id = $_GET['id'] ?? null;

if (!$id) {
    setFlashMessage('error', 'Invalid product ID');
    redirect(baseUrl('admin/products/list.php'));
}

$productModel = new Product();
try {
    if ($productModel->deleteProduct($id)) {
        setFlashMessage('success', 'Product deleted successfully');
    } else {
        setFlashMessage('error', 'Failed to delete product');
    }
} catch (Exception $e) {
    setFlashMessage('error', 'Error: ' . $e->getMessage());
}

redirect(baseUrl('admin/products/list.php'));
