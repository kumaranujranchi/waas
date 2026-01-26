<?php
/**
 * Application Configuration
 */

// Site Settings
define('SITE_NAME', 'SiteOnSub');
define('SITE_TAGLINE', 'WaaS Marketplace');
// Dynamic Site URL detection
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if ($host === 'localhost' || strpos($host, '127.0.0.1') !== false || strpos($host, '192.168.') !== false) {
    define('SITE_URL', $protocol . '://' . $host);
} else {
    define('SITE_URL', 'https://siteonsub.com'); // Production URL
}
define('SITE_EMAIL', 'support@siteonsub.com');

// Paths
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOAD_PATH', BASE_PATH . '/uploads');

// Currency Settings
if (!defined('CURRENCY'))
    define('CURRENCY', 'INR');
if (!defined('CURRENCY_SYMBOL'))
    define('CURRENCY_SYMBOL', '₹');
if (!defined('TAX_RATE'))
    define('TAX_RATE', 0.18); // 18% GST

// Pagination
define('ITEMS_PER_PAGE', 12);

// Session Settings
define('SESSION_LIFETIME', 3600); // 1 hour

// Payment Gateway Settings
define('PAYMENT_GATEWAY', 'stripe'); // 'stripe' or 'paypal'

// Stripe Configuration (Test Mode)
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_YOUR_KEY_HERE');
define('STRIPE_SECRET_KEY', 'sk_test_YOUR_KEY_HERE');

// Razorpay Configuration
define('RAZORPAY_KEY_ID', 'rzp_test_S8VnZcp2OFweOn');
define('RAZORPAY_KEY_SECRET', 'N1Df1w3L2vwYv1HHTiGwG0bA');

// Razorpay Plan Mapping (Internal Plan ID => Razorpay Plan ID)
// format: 'internal_db_id' => 'razorpay_plan_id'
$RAZORPAY_PLANS = [
    '1' => 'plan_S8Wasx7b5ThF2A', // Updated
    '2' => 'plan_six_months_default',
    '3' => 'plan_yearly_default',
    '55' => 'plan_S8Wasx7b5ThF2A', // Added for testing
    '139' => 'plan_S8Wasx7b5ThF2A', // Added for testing
    'default' => 'plan_S8Wasx7b5ThF2A' // Fallback for any other test plan
];

// PayPal Configuration (Sandbox)
define('PAYPAL_CLIENT_ID', 'AXBbDsmr2jyXH5Gqo1wPCP5bEGaH0nkr2OloEu6qKN-wdqpDxGpFHno3DNhxAqtPDZ1eYxNUfdv-rmaN');
define('PAYPAL_SECRET', 'EAMQKv-QkcTjmL2Fg_NT2dxaymYderDT0qKIpWZ8bbVICrZGlky9LWsyimapIfY0W4F8vs7EIaAkclUs');
define('PAYPAL_MODE', 'sandbox'); // 'sandbox' or 'live'

// Google Auth Configuration
if (!defined('GOOGLE_CLIENT_ID'))
    define('GOOGLE_CLIENT_ID', '1062996273887-oc0bb2af0rrnov0p6ft5foigon08cjfi.apps.googleusercontent.com');
if (!defined('GOOGLE_CLIENT_SECRET'))
    define('GOOGLE_CLIENT_SECRET', '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');
if (!defined('GOOGLE_REDIRECT_URI'))
    define('GOOGLE_REDIRECT_URI', SITE_URL . '/auth/google_callback.php');

// Email Settings (SMTP)
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'support@siteonsub.com');
define('SMTP_PASSWORD', 'YOUR_SMTP_PASSWORD_HERE');
define('SMTP_ENCRYPTION', 'tls');

// Security
define('PASSWORD_MIN_LENGTH', 8);
define('HASH_ALGO', PASSWORD_DEFAULT);

// Error Reporting (set to false in production)
define('DEBUG_MODE', false); // PRODUCTION: Debug disabled

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
