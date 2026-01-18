<?php
/**
 * Logout Handler
 */

session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Destroy session
session_destroy();

setFlashMessage('success', 'You have been logged out successfully');
redirect(baseUrl('index.php'));
