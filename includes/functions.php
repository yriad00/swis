<?php
/**
 * Swis Brands - Core Functions
 * Utility functions used throughout the application
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('SWIS_ROOT')) {
    die('Direct access not allowed');
}

/**
 * Load and decode a JSON file safely
 * 
 * @param string $filePath Path to the JSON file
 * @param bool $dieOnError Whether to die on error or return null
 * @return array|null Decoded JSON data or null on failure
 */
function loadJsonFile(string $filePath, bool $dieOnError = true): ?array {
    if (!file_exists($filePath)) {
        if ($dieOnError) {
            http_response_code(500);
            die('Error: Required data file not found.');
        }
        return null;
    }
    
    $content = file_get_contents($filePath);
    if ($content === false) {
        if ($dieOnError) {
            http_response_code(500);
            die('Error: Could not read data file.');
        }
        return null;
    }
    
    $data = json_decode($content, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        if ($dieOnError) {
            http_response_code(500);
            die('Error: Invalid JSON format in data file.');
        }
        return null;
    }
    
    return $data;
}

/**
 * Save data to a JSON file safely
 * 
 * @param string $filePath Path to the JSON file
 * @param array $data Data to encode and save
 * @return bool True on success, false on failure
 */
function saveJsonFile(string $filePath, array $data): bool {
    $jsonOptions = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    $content = json_encode($data, $jsonOptions);
    
    if ($content === false) {
        return false;
    }
    
    // Write to temp file first, then move (atomic write)
    $tempFile = $filePath . '.tmp';
    if (file_put_contents($tempFile, $content, LOCK_EX) === false) {
        return false;
    }
    
    return rename($tempFile, $filePath);
}

/**
 * Sanitize a string for safe HTML output
 * 
 * @param string|null $string The string to sanitize
 * @return string Sanitized string
 */
function sanitize(?string $string): string {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitize a string and strip all HTML tags
 * 
 * @param string|null $string The string to sanitize
 * @return string Sanitized string without HTML
 */
function sanitizeStrict(?string $string): string {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Get settings from the settings file
 * 
 * @return array Settings array
 */
function getSettings(): array {
    static $settings = null;
    
    if ($settings === null) {
        $settings = loadJsonFile(SETTINGS_FILE);
    }
    
    return $settings ?? [];
}

/**
 * Get tracking configuration
 * 
 * @return array Tracking configuration
 */
function getTrackingConfig(): array {
    static $tracking = null;
    
    if ($tracking === null) {
        $tracking = loadJsonFile(TRACKING_FILE, false);
    }
    
    return $tracking ?? [
        'facebook_pixel_id' => '',
        'google_analytics_id' => '',
        'google_ads_id' => '',
        'tiktok_pixel_id' => '',
        'snapchat_pixel_id' => '',
        'custom_head_scripts' => '',
        'custom_body_scripts' => ''
    ];
}

/**
 * Get all products data
 * 
 * @return array Products data
 */
function getProductsData(): array {
    static $products = null;
    
    if ($products === null) {
        $products = loadJsonFile(PRODUCTS_FILE);
    }
    
    return $products ?? ['newArrivals' => [], 'allProducts' => [], 'promoPacks' => []];
}

/**
 * Find a product by ID across all categories
 * 
 * @param int $id Product ID
 * @return array|null Product data or null if not found
 */
function findProductById(int $id): ?array {
    $data = getProductsData();
    
    foreach (['newArrivals', 'allProducts', 'promoPacks'] as $category) {
        foreach ($data[$category] ?? [] as $product) {
            if (isset($product['id']) && $product['id'] == $id) {
                return [
                    'product' => $product,
                    'type' => $category === 'newArrivals' ? 'new' : 
                             ($category === 'promoPacks' ? 'pack' : 'collection')
                ];
            }
        }
    }
    
    return null;
}

/**
 * Build a full image URL from a relative path
 * 
 * @param string $imagePath The image path (relative or absolute)
 * @return string Full URL to the image
 */
function getImageUrl(string $imagePath): string {
    if (empty($imagePath)) {
        return SITE_URL . '/images/placeholder.jpg';
    }
    
    // Already a full URL
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }
    
    // Ensure leading slash
    if (strpos($imagePath, '/') !== 0) {
        $imagePath = '/' . $imagePath;
    }
    
    return SITE_URL . $imagePath;
}

/**
 * Validate an uploaded image file
 * 
 * @param array $file The $_FILES array entry
 * @return array ['valid' => bool, 'error' => string|null, 'extension' => string|null]
 */
function validateImageUpload(array $file): array {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds server size limit',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form size limit',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file',
            UPLOAD_ERR_EXTENSION => 'Upload blocked by extension'
        ];
        return [
            'valid' => false,
            'error' => $errors[$file['error']] ?? 'Unknown upload error',
            'extension' => null
        ];
    }
    
    // Check file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return [
            'valid' => false,
            'error' => 'File too large (max: ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . 'MB)',
            'extension' => null
        ];
    }
    
    // Check extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_IMAGE_EXTENSIONS)) {
        return [
            'valid' => false,
            'error' => 'Invalid file extension',
            'extension' => null
        ];
    }
    
    // Check MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    if (!in_array($mimeType, ALLOWED_IMAGE_MIMETYPES)) {
        return [
            'valid' => false,
            'error' => 'Invalid file type',
            'extension' => null
        ];
    }
    
    return [
        'valid' => true,
        'error' => null,
        'extension' => $extension
    ];
}

/**
 * Generate a unique filename for upload
 * 
 * @param string $extension File extension
 * @param string $prefix Optional prefix
 * @return string Unique filename
 */
function generateUploadFilename(string $extension, string $prefix = ''): string {
    $timestamp = time();
    $unique = bin2hex(random_bytes(8));
    return $prefix . $timestamp . '_' . $unique . '.' . $extension;
}

/**
 * Handle image upload and return the path
 * 
 * @param array $file The $_FILES array entry
 * @param string $prefix Optional filename prefix
 * @return array ['success' => bool, 'path' => string|null, 'error' => string|null]
 */
function handleImageUpload(array $file, string $prefix = ''): array {
    $validation = validateImageUpload($file);
    if (!$validation['valid']) {
        return [
            'success' => false,
            'path' => null,
            'error' => $validation['error']
        ];
    }
    
    // Ensure upload directory exists
    if (!is_dir(UPLOADS_PATH)) {
        mkdir(UPLOADS_PATH, 0755, true);
    }
    
    $filename = generateUploadFilename($validation['extension'], $prefix);
    $targetPath = UPLOADS_PATH . '/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return [
            'success' => false,
            'path' => null,
            'error' => 'Failed to save uploaded file'
        ];
    }
    
    return [
        'success' => true,
        'path' => '/uploads/' . $filename,
        'error' => null
    ];
}

/**
 * Delete an uploaded file safely
 * 
 * @param string $path Relative path to the file
 * @return bool True on success
 */
function deleteUploadedFile(string $path): bool {
    if (empty($path)) {
        return false;
    }
    
    // Security: Ensure path is within uploads directory
    $fullPath = SWIS_ROOT . $path;
    $realPath = realpath($fullPath);
    
    if ($realPath === false) {
        return false;
    }
    
    // Check the file is within allowed directories
    $allowedDirs = [realpath(UPLOADS_PATH), realpath(IMAGES_PATH)];
    $isAllowed = false;
    foreach ($allowedDirs as $dir) {
        if ($dir && strpos($realPath, $dir) === 0) {
            $isAllowed = true;
            break;
        }
    }
    
    if (!$isAllowed) {
        return false;
    }
    
    return file_exists($realPath) && unlink($realPath);
}

/**
 * Format price for display
 * 
 * @param float|int $price The price value
 * @param string|null $currency Currency symbol (defaults to settings)
 * @return string Formatted price
 */
function formatPrice($price, ?string $currency = null): string {
    if ($currency === null) {
        $settings = getSettings();
        $currency = $settings['currency'] ?? 'DH';
    }
    return number_format((float)$price, 0, ',', ' ') . ' ' . $currency;
}

/**
 * Generate a safe JSON output for JavaScript
 * 
 * @param mixed $data Data to encode
 * @return string JSON string safe for embedding in HTML
 */
function safeJsonEncode($data): string {
    $options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE;
    return json_encode($data, $options);
}

/**
 * Check if current request is HTTPS
 * 
 * @return bool True if HTTPS
 */
function isSecureConnection(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
}

/**
 * Redirect to a URL
 * 
 * @param string $url Target URL
 * @param int $statusCode HTTP status code
 */
function redirect(string $url, int $statusCode = 302): void {
    header("Location: $url", true, $statusCode);
    exit;
}
