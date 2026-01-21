<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (isset($token['error'])) {
            throw new Exception("Error fetching token: " . $token['error']);
        }

        $client->setAccessToken($token['access_token']);

        // Get user profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $userModel = new User();
        $result = $userModel->findOrCreateByGoogle($google_account_info);

        if ($result['success']) {
            $user = $result['user'];

            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];

            if (!empty($user['avatar'])) {
                $_SESSION['user_avatar'] = $user['avatar'];
            }

            setFlashMessage('success', 'Logged in with Google successfully!');

            if ($user['role'] === 'admin') {
                redirect(baseUrl('admin/index.php'));
            } else {
                redirect(baseUrl('dashboard/index.php'));
            }
        } else {
            setFlashMessage('error', 'Google authentication failed: ' . $result['message']);
            redirect(baseUrl('auth/login.php'));
        }

    } catch (Exception $e) {
        setFlashMessage('error', 'Authentication error: ' . $e->getMessage());
        redirect(baseUrl('auth/login.php'));
    }
} else {
    redirect(baseUrl('auth/login.php'));
}
