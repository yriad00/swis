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
?>