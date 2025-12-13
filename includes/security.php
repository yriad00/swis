<?php
/**
 * Swis Brands - Security Functions
 * Security headers, CSRF protection, and input validation
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('SWIS_ROOT')) {
    die('Direct access not allowed');
}

/**
 * Set security headers for all pages
 * 
 * @param bool $isAdmin Whether this is an admin page (stricter settings)
 */
function setSecurityHeaders(bool $isAdmin = false): void {
    // Prevent MIME sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Clickjacking protection
    header('X-Frame-Options: ' . ($isAdmin ? 'DENY' : 'SAMEORIGIN'));
    
    // XSS protection (legacy browsers)
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions policy
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    
    // Content Security Policy (basic - adjust as needed)
    if ($isAdmin) {
        // Stricter CSP for admin
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com; " .
               "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: blob: https:; " .
               "connect-src 'self';";
    } else {
        // More permissive for public site (tracking pixels, etc.)
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://connect.facebook.net https://www.googletagmanager.com https://www.google-analytics.com https://analytics.tiktok.com https://sc-static.net; " .
               "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: blob: https:; " .
               "connect-src 'self' https://www.facebook.com https://www.google-analytics.com https://analytics.tiktok.com https://tr.snapchat.com https://api.whatsapp.com; " .
               "frame-src https://www.facebook.com https://www.youtube.com;";
    }
    header("Content-Security-Policy: $csp");
}

/**
 * Configure secure session settings
 */
function configureSecureSession(): void {
    // Only configure if session not already started
    if (session_status() === PHP_SESSION_NONE) {
        // HTTP-only cookies (not accessible via JavaScript)
        ini_set('session.cookie_httponly', 1);
        
        // Same-site cookie policy
        ini_set('session.cookie_samesite', 'Strict');
        
        // Secure cookies only on HTTPS
        if (isSecureConnection()) {
            ini_set('session.cookie_secure', 1);
        }
        
        // Use strict session mode
        ini_set('session.use_strict_mode', 1);
        
        // Regenerate session ID periodically
        ini_set('session.gc_maxlifetime', 3600); // 1 hour
        
        session_start();
    }
}

/**
 * Start a secure session (alias for common use)
 */
function startSecureSession(): void {
    configureSecureSession();
}

/**
 * Generate a CSRF token
 * 
 * @return string The CSRF token
 */
function generateCsrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) {
        configureSecureSession();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Regenerate CSRF token (after successful form submission)
 * 
 * @return string New CSRF token
 */
function regenerateCsrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) {
        configureSecureSession();
    }
    
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

/**
 * Validate a CSRF token
 * 
 * @param string|null $token The token to validate
 * @return bool True if valid
 */
function validateCsrfToken(?string $token = null): bool {
    if ($token === null) {
        $token = $_POST['csrf_token'] ?? '';
    }
    
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF token as hidden input field HTML
 * 
 * @return string HTML input element
 */
function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . sanitize(generateCsrfToken()) . '">';
}

/**
 * Require valid CSRF token for POST requests
 * Dies with error if invalid
 */
function requireCsrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!validateCsrfToken()) {
            http_response_code(403);
            die('Invalid security token. Please refresh and try again.');
        }
    }
}

/**
 * Rate limiting helper (simple implementation using session)
 * 
 * @param string $action Action identifier
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $windowSeconds Time window in seconds
 * @return bool True if allowed, false if rate limited
 */
function checkRateLimit(string $action, int $maxAttempts = 5, int $windowSeconds = 60): bool {
    if (session_status() === PHP_SESSION_NONE) {
        configureSecureSession();
    }
    
    $key = 'rate_limit_' . $action;
    $now = time();
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'start' => $now];
    }
    
    $data = &$_SESSION[$key];
    
    // Reset if window has passed
    if ($now - $data['start'] > $windowSeconds) {
        $data = ['count' => 0, 'start' => $now];
    }
    
    $data['count']++;
    
    return $data['count'] <= $maxAttempts;
}

/**
 * Validate that a value is within an allowed whitelist
 * 
 * @param mixed $value The value to check
 * @param array $allowed Allowed values
 * @param mixed $default Default value if not in whitelist
 * @return mixed The value if allowed, otherwise default
 */
function validateWhitelist($value, array $allowed, $default = null) {
    return in_array($value, $allowed, true) ? $value : $default;
}

/**
 * Validate and sanitize an integer input
 * 
 * @param mixed $value The value to validate
 * @param int $min Minimum allowed value
 * @param int|null $max Maximum allowed value (null for no limit)
 * @param int $default Default value
 * @return int Validated integer
 */
function validateInt($value, int $min = 0, ?int $max = null, int $default = 0): int {
    $options = ['min_range' => $min];
    if ($max !== null) {
        $options['max_range'] = $max;
    }
    
    $filtered = filter_var($value, FILTER_VALIDATE_INT, ['options' => $options]);
    return $filtered !== false ? $filtered : $default;
}

/**
 * Validate a phone number (Moroccan format)
 * 
 * @param string $phone Phone number
 * @return string|false Cleaned phone number or false if invalid
 */
function validatePhone(string $phone) {
    // Remove all non-digit characters
    $cleaned = preg_replace('/[^0-9]/', '', $phone);
    
    // Check for valid Moroccan phone number patterns
    // 06XXXXXXXX, 07XXXXXXXX (10 digits local)
    // 212XXXXXXXXX (12 digits international)
    if (preg_match('/^(0[67]\d{8}|212[67]\d{8})$/', $cleaned)) {
        return $cleaned;
    }
    
    return false;
}

/**
 * Sanitize a filename for safe storage
 * 
 * @param string $filename Original filename
 * @return string Safe filename
 */
function sanitizeFilename(string $filename): string {
    // Remove directory traversal attempts
    $filename = basename($filename);
    
    // Remove special characters except alphanumeric, dots, dashes, underscores
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    
    // Limit length
    if (strlen($filename) > 255) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $filename = substr($name, 0, 250 - strlen($ext)) . '.' . $ext;
    }
    
    return $filename ?: 'file';
}
