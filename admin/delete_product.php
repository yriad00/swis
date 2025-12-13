<?php
/**
 * Swis Brands - Delete Product
 * Handles product deletion with image cleanup
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

require_once __DIR__ . '/../includes/admin-bootstrap.php';

// Only accept POST requests for destructive actions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

// Validate CSRF token
if (!validateCsrfToken()) {
    redirect('index.php?error=csrf');
}

$id = $_POST['id'] ?? null;
$type = $_POST['type'] ?? null;

// Whitelist allowed types to prevent arbitrary key deletion
$allowedTypes = ['newArrivals', 'allProducts', 'promoPacks'];

if ($id && $type && in_array($type, $allowedTypes, true)) {
    $data = getProductsData();

    if (isset($data[$type])) {
        $originalCount = count($data[$type]);
        $data[$type] = array_filter($data[$type], function($p) use ($id) {
            if ($p['id'] == $id) {
                // Delete main image
                if (!empty($p['image'])) {
                    deleteUploadedFile($p['image']);
                }
                // Delete gallery images
                if (!empty($p['gallery'])) {
                    foreach ($p['gallery'] as $galleryImg) {
                        deleteUploadedFile($galleryImg);
                    }
                }
                // Delete variant images
                if (!empty($p['variants'])) {
                    foreach ($p['variants'] as $variant) {
                        if (!empty($variant['image'])) {
                            deleteUploadedFile($variant['image']);
                        }
                        if (!empty($variant['gallery'])) {
                            foreach ($variant['gallery'] as $vGalleryImg) {
                                deleteUploadedFile($vGalleryImg);
                            }
                        }
                    }
                }
                return false; // Remove from array
            }
            return true; // Keep in array
        });
        // Re-index array
        $data[$type] = array_values($data[$type]);
        
        // Only write if something was actually deleted
        if (count($data[$type]) < $originalCount) {
            saveJsonFile(PRODUCTS_FILE, $data);
        }
    }
}

redirect('index.php');
?>