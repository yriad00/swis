<?php
/**
 * Swis Brands - Admin Bootstrap File
 * This file initializes the admin section
 * 
 * Include this file at the top of admin pages:
 * require_once __DIR__ . '/../includes/admin-bootstrap.php';
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Define root path constant
define('SWIS_ROOT', dirname(__DIR__));

// Load configuration
require_once SWIS_ROOT . '/includes/config.php';

// Load core functions
require_once SWIS_ROOT . '/includes/functions.php';

// Load security functions
require_once SWIS_ROOT . '/includes/security.php';

// Load pixel tracking (Facebook + TikTok) - for admin functions that need it
require_once SWIS_ROOT . '/includes/pixels.php';

// Set security headers for admin pages (stricter)
setSecurityHeaders(true);

// Start secure session
startSecureSession();

// Check authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Get the correct path for redirect
    $path = dirname($_SERVER['PHP_SELF']);
    $path = str_replace('\\', '/', $path);
    $path = rtrim($path, '/');
    
    redirect("$path/login.php");
}

// Generate CSRF token
generateCsrfToken();
