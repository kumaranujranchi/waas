<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(baseUrl('dashboard/index.php'));
}

// Store redirect URL if present
if (isset($_GET['redirect'])) {
    $_SESSION['login_redirect'] = urldecode($_GET['redirect']);
}

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

$authUrl = $client->createAuthUrl();
redirect($authUrl);
