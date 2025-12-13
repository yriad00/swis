<?php
/**
 * Swis Brands - Configuration File
 * Central configuration for the entire application
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('SWIS_ROOT')) {
    define('SWIS_ROOT', dirname(__DIR__));
}

// Environment (production, development)
define('SWIS_ENV', 'production');

// Error reporting based on environment
if (SWIS_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', SWIS_ROOT . '/data/error.log');
}

// Site configuration
define('SITE_URL', 'https://swisbrands.ma');
define('SITE_NAME', 'Swis Brands');

// Data paths
define('DATA_PATH', SWIS_ROOT . '/data');
define('UPLOADS_PATH', SWIS_ROOT . '/uploads');
define('IMAGES_PATH', SWIS_ROOT . '/images');

// File paths
define('SETTINGS_FILE', DATA_PATH . '/settings.json');
define('PRODUCTS_FILE', DATA_PATH . '/products.json');
define('CREDENTIALS_FILE', DATA_PATH . '/admin_credentials.json');
define('TRACKING_FILE', DATA_PATH . '/tracking.json');

// Allowed image extensions and MIME types
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_IMAGE_MIMETYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Maximum upload size (in bytes) - 10MB
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024);

// Cache settings
define('CACHE_ENABLED', true);
define('CACHE_TTL', 3600); // 1 hour
