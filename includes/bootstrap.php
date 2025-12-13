<?php
/**
 * Swis Brands - Bootstrap File
 * This file initializes the application and loads all required components
 * 
 * Include this file at the top of every page:
 * require_once __DIR__ . '/includes/bootstrap.php';
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Define root path constant (parent of includes folder)
define('SWIS_ROOT', dirname(__DIR__));

// Load configuration
require_once SWIS_ROOT . '/includes/config.php';

// Load core functions
require_once SWIS_ROOT . '/includes/functions.php';

// Load security functions
require_once SWIS_ROOT . '/includes/security.php';

// Load pixel tracking (Facebook + TikTok)
require_once SWIS_ROOT . '/includes/pixels.php';

// Set security headers for public pages
setSecurityHeaders(false);
