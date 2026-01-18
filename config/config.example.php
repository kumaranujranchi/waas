<?php
/**
 * Application Configuration - EXAMPLE
 * 
 * Copy this file to config.php and update with your settings
 */

// Site Settings
define('SITE_NAME', 'WaaS Marketplace');
define('SITE_URL', 'https://honestchoicereview.com');
define('SITE_EMAIL', 'info@honestchoicereview.com');

// Paths
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOAD_PATH', BASE_PATH . '/uploads');

// Currency Settings
define('CURRENCY', 'USD');
define('CURRENCY_SYMBOL', '$');
define('TAX_RATE', 0.08); // 8% tax

// Pagination
define('ITEMS_PER_PAGE', 12);

// Session Settings
define('SESSION_LIFETIME', 3600); // 1 hour

// Payment Gateway Settings
define('PAYMENT_GATEWAY', 'stripe'); // 'stripe' or 'paypal'

// Stripe Configuration (Test Mode)
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_YOUR_KEY_HERE');
define('STRIPE_SECRET_KEY', 'sk_test_YOUR_KEY_HERE');

// PayPal Configuration (Sandbox)
define('PAYPAL_CLIENT_ID', 'YOUR_PAYPAL_CLIENT_ID');
define('PAYPAL_SECRET', 'YOUR_PAYPAL_SECRET');
define('PAYPAL_MODE', 'sandbox'); // 'sandbox' or 'live'

// Email Settings (SMTP)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_ENCRYPTION', 'tls');

// Security
define('PASSWORD_MIN_LENGTH', 8);
define('HASH_ALGO', PASSWORD_DEFAULT);

// Error Reporting (set to false in production)
define('DEBUG_MODE', false);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
