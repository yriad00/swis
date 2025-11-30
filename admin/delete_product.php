<?php
require_once 'auth.php';

// Only accept POST requests for destructive actions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Validate CSRF token
if (!validateCsrfToken()) {
    header('Location: index.php?error=csrf');
    exit;
}

$id = $_POST['id'] ?? null;
$type = $_POST['type'] ?? null;

// Whitelist allowed types to prevent arbitrary key deletion
$allowedTypes = ['newArrivals', 'allProducts', 'promoPacks'];

if ($id && $type && in_array($type, $allowedTypes, true)) {
    $jsonFile = '../data/products.json';
    
    if (!file_exists($jsonFile)) {
        header('Location: index.php?error=file_not_found');
        exit;
    }
    
    $jsonData = file_get_contents($jsonFile);
    $data = json_decode($jsonData, true);
    
    if ($data === null) {
        header('Location: index.php?error=invalid_json');
        exit;
    }

    if (isset($data[$type])) {
        $originalCount = count($data[$type]);
        $data[$type] = array_filter($data[$type], function($p) use ($id) {
            if ($p['id'] == $id) {
                // Delete main image
                if (!empty($p['image'])) {
                    $imagePath = __DIR__ . '/..' . $p['image'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                // Delete gallery images
                if (!empty($p['gallery'])) {
                    foreach ($p['gallery'] as $galleryImg) {
                        $galleryPath = __DIR__ . '/..' . $galleryImg;
                        if (file_exists($galleryPath)) {
                            unlink($galleryPath);
                        }
                    }
                }
                // Delete variant images
                if (!empty($p['variants'])) {
                    foreach ($p['variants'] as $variant) {
                        if (!empty($variant['image'])) {
                            $variantPath = __DIR__ . '/..' . $variant['image'];
                            if (file_exists($variantPath)) {
                                unlink($variantPath);
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
            file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}

header('Location: index.php');
exit;
?>