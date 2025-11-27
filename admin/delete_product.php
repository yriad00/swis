<?php
require_once 'auth.php';

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null;

if ($id && $type) {
    $jsonData = file_get_contents('../data/products.json');
    $data = json_decode($jsonData, true);

    if (isset($data[$type])) {
        $data[$type] = array_filter($data[$type], function($p) use ($id) {
            return $p['id'] != $id;
        });
        // Re-index array
        $data[$type] = array_values($data[$type]);
        
        file_put_contents('../data/products.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

header('Location: index.php');
exit;
?>