<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Use absolute path for redirect to avoid issues with missing trailing slashes in URL
    // e.g. accessing /admin instead of /admin/
    $path = dirname($_SERVER['PHP_SELF']);
    $path = str_replace('\\', '/', $path); // Fix Windows paths
    $path = rtrim($path, '/');
    
    header("Location: $path/login.php");
    exit;
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Validate CSRF token for POST requests
 * @return bool
 */
function validateCsrfToken(): bool {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true; // Only validate POST requests
    }
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

/**
 * Get CSRF token HTML input
 * @return string
 */
function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}
?>