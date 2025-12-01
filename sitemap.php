<?php
// Security Headers
header('X-Content-Type-Options: nosniff');
header("Content-Type: application/xml; charset=utf-8");

$jsonData = file_get_contents('data/products.json');
$data = json_decode($jsonData, true);
$baseUrl = "https://swisbrands.ma"; // Change this to your actual domain

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo $baseUrl; ?>/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <?php
    function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return $text;
    }

    $allProducts = array_merge(
        $data['newArrivals'] ?? [], 
        $data['allProducts'] ?? [], 
        $data['promoPacks'] ?? []
    );

    // Remove duplicates based on ID
    $uniqueProducts = [];
    foreach ($allProducts as $p) {
        $uniqueProducts[$p['id']] = $p;
    }

    foreach ($uniqueProducts as $product):
        // Skip products with empty names
        if (empty($product['name'])) continue;
        
        $slug = slugify($product['name']);
        // Use query string format to match product.php
        $url = $baseUrl . "/product.php?id=" . $product['id'];
    ?>
    <url>
        <loc><?php echo htmlspecialchars($url); ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <?php endforeach; ?>
</urlset>