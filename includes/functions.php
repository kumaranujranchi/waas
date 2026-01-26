<?php
/**
 * Helper Functions
 */

/**
 * Sanitize input data
 */
function sanitizeInput($data)
{
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Get current user ID
 */
function getCurrentUserId()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user data
 */
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    require_once __DIR__ . '/../classes/Database.php';
    $db = Database::getInstance();
    return $db->fetchOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
}

/**
 * Redirect to URL
 */
function redirect($url)
{
    header("Location: " . $url);
    exit();
}

/**
 * Format price with currency symbol
 */
function formatPrice($amount, $currency = '₹')
{
    $val = floatval($amount);
    return $currency . ' ' . number_format($val, 2);
}

/**
 * Calculate tax amount
 */
function calculateTax($amount, $rate = TAX_RATE)
{
    return $amount * $rate;
}

/**
 * Generate unique order number
 */
function generateOrderNumber()
{
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

/**
 * Hash password
 */
function hashPassword($password)
{
    return password_hash($password, HASH_ALGO);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * Validate email
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate slug from string
 */
function generateSlug($string)
{
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Flash message functions
 */
function setFlashMessage($type, $message)
{
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'error', 'warning', 'info'
        'message' => $message
    ];
}

function getFlashMessage()
{
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

function hasFlashMessage()
{
    return isset($_SESSION['flash_message']);
}

/**
 * Get base URL
 */
function baseUrl($path = '')
{
    // Remove .php extension from path if it exists, preserving query params and fragments
    $cleanPath = preg_replace('/\.php(\?|#|$)/', '$1', $path);
    return SITE_URL . '/' . ltrim($cleanPath, '/');
}

/**
 * Get asset URL
 */
function asset($path)
{
    return ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Escape output
 */
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Debug function
 */
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * Format date
 */
function formatDate($date, $format = 'M d, Y')
{
    return date($format, strtotime($date));
}

/**
 * Time ago function
 */
function timeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;

    $periods = [
        'year' => 31536000,
        'month' => 2592000,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1
    ];

    foreach ($periods as $key => $value) {
        if ($difference >= $value) {
            $time = floor($difference / $value);
            return $time . ' ' . $key . ($time > 1 ? 's' : '') . ' ago';
        }
    }

    return 'Just now';
}

/**
 * Require login
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Please login to continue');
        redirect(baseUrl('auth/login.php'));
    }
}

/**
 * Require admin
 */
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        setFlashMessage('error', 'Access denied');
        redirect(baseUrl('index.php'));
    }
}

/**
 * CSRF Token functions
 */
function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Create Razorpay Subscription
 */
function createRazorpaySubscription($planId, $totalCount = 120, $startAt = null)
{
    $apiKey = defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : '';
    $apiSecret = defined('RAZORPAY_KEY_SECRET') ? RAZORPAY_KEY_SECRET : '';

    if (empty($apiKey) || empty($apiSecret)) {
        return ['error' => 'Razorpay keys not configured'];
    }

    $data = [
        'plan_id' => $planId,
        'total_count' => $totalCount, // Number of billing cycles
        'quantity' => 1,
        'customer_notify' => 1,
    ];

    if ($startAt) {
        $data['start_at'] = $startAt;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/subscriptions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':' . $apiSecret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }
    curl_close($ch);

    return json_decode($result, true);
}

/**
 * Get PayPal API Access Token
 */
function getPayPalAccessToken()
{
    $clientId = defined('PAYPAL_CLIENT_ID') ? PAYPAL_CLIENT_ID : '';
    $secret = defined('PAYPAL_SECRET') ? PAYPAL_SECRET : '';
    $mode = defined('PAYPAL_MODE') ? PAYPAL_MODE : 'sandbox';

    if (empty($clientId) || empty($secret)) {
        return ['error' => 'PayPal credentials not configured'];
    }

    $url = ($mode === 'live')
        ? 'https://api-m.paypal.com/v1/oauth2/token'
        : 'https://api-m.sandbox.paypal.com/v1/oauth2/token';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }
    curl_close($ch);

    $response = json_decode($result, true);
    return $response['access_token'] ?? ['error' => 'Failed to get PayPal access token'];
}

/**
 * Create PayPal Subscription
 */
function createPayPalSubscription($planId)
{
    $token = getPayPalAccessToken();
    if (is_array($token) && isset($token['error'])) {
        return $token;
    }

    $mode = defined('PAYPAL_MODE') ? PAYPAL_MODE : 'sandbox';
    $url = ($mode === 'live')
        ? 'https://api-m.paypal.com/v1/billing/subscriptions'
        : 'https://api-m.sandbox.paypal.com/v1/billing/subscriptions';

    $data = [
        'plan_id' => $planId,
        'application_context' => [
            'brand_name' => defined('SITE_NAME') ? SITE_NAME : 'SiteOnSub',
            'locale' => 'en-US',
            'shipping_preference' => 'NO_SHIPPING',
            'user_action' => 'SUBSCRIBE_NOW',
            'return_url' => baseUrl('paypal_callback.php?status=success'),
            'cancel_url' => baseUrl('paypal_callback.php?status=cancel')
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
        'PayPal-Request-Id: ' . uniqid()
    ]);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }
    curl_close($ch);

    return json_decode($result, true);
}

/**
 * Verify PayPal Subscription Status
 */
function verifyPayPalSubscription($subscriptionId)
{
    $token = getPayPalAccessToken();
    if (is_array($token) && isset($token['error'])) {
        return $token;
    }

    $mode = defined('PAYPAL_MODE') ? PAYPAL_MODE : 'sandbox';
    $url = ($mode === 'live')
        ? "https://api-m.paypal.com/v1/billing/subscriptions/{$subscriptionId}"
        : "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/{$subscriptionId}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }
    curl_close($ch);

    return json_decode($result, true);
}
