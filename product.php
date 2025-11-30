<?php
// Load settings with error handling
$settingsFile = 'data/settings.json';
if (!file_exists($settingsFile)) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "Configuration error";
    exit;
}
$settings = json_decode(file_get_contents($settingsFile), true);
if ($settings === null) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "Configuration error";
    exit;
}

// Load products with error handling
$productsFile = 'data/products.json';
if (!file_exists($productsFile)) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "Products data not found";
    exit;
}
$jsonData = file_get_contents($productsFile);
$data = json_decode($jsonData, true);
if ($data === null) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "Invalid products data";
    exit;
}

$id = $_GET['id'] ?? null;
$product = null;
$type = 'collection';

// Search for product in all categories
if ($id) {
    foreach ($data['newArrivals'] as $p) {
        if ($p['id'] == $id) {
            $product = $p;
            $type = 'new';
            break;
        }
    }
    if (!$product) {
        foreach ($data['allProducts'] as $p) {
            if ($p['id'] == $id) {
                $product = $p;
                $type = 'collection';
                break;
            }
        }
    }
    if (!$product) {
        foreach ($data['promoPacks'] as $p) {
            if ($p['id'] == $id) {
                $product = $p;
                $type = 'pack';
                break;
            }
        }
    }
}

if (!$product) {
    header("HTTP/1.0 404 Not Found");
    echo "Produit non trouvé / Product not found";
    exit;
}

// Prepare Dynamic Meta Data
$pageTitle = htmlspecialchars($product['name']) . " - " . htmlspecialchars($settings['site_title']);
$pageDesc = "Achetez " . htmlspecialchars($product['name']) . ". " . ($product['description'] ?? "Montre de luxe pour femme au Maroc. Qualité premium et livraison gratuite.");
$pageKeywords = "montre femme, " . htmlspecialchars($product['name']) . ", " . htmlspecialchars($product['category']) . ", luxe maroc";
$currentPrice = ($type === 'pack') ? $product['newPrice'] : $product['price'];
$oldPrice = ($type === 'pack') ? $product['oldPrice'] : ($product['oldPrice'] ?? null);

// Fix image URL - handle both absolute and relative paths
$productImage = $product['image'];
if (strpos($productImage, 'http') === 0) {
    $imageUrl = $productImage;
} elseif (strpos($productImage, '/') === 0) {
    // Relative path starting with / - prepend domain
    $imageUrl = "https://swisbrands.ma" . $productImage;
} else {
    // Relative path without leading / - prepend domain and /
    $imageUrl = "https://swisbrands.ma/" . $productImage;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- 2. Dynamic Meta Tags -->
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDesc; ?>">
    <meta name="keywords" content="<?php echo $pageKeywords; ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $pageTitle; ?>">
    <meta property="og:description" content="<?php echo $pageDesc; ?>">
    <meta property="og:image" content="<?php echo $imageUrl; ?>">
    <meta property="og:url" content="https://swisbrands.ma/product.php?id=<?php echo $id; ?>">
    <meta property="og:type" content="product">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Cairo:wght@300;400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            gold: '#D4AF37',
                            goldLight: '#F3E5AB',
                            black: '#1a1a1a',
                            light: '#f9f9f9',
                            rose: '#E6C0C0',
                            red: '#C41E3A'
                        }
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Poppins', 'sans-serif'],
                        arabic: ['Cairo', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- 3. Structured Data (Schema.org) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "<?php echo htmlspecialchars($product['name']); ?>",
      "image": [
        "<?php echo $imageUrl; ?>"
       ],
      "description": "<?php echo htmlspecialchars($pageDesc); ?>",
      "sku": "<?php echo $product['id']; ?>",
      "brand": {
        "@type": "Brand",
        "name": "Swis Brands"
      },
      "offers": {
        "@type": "Offer",
        "url": "https://swisbrands.ma/product.php?id=<?php echo $id; ?>",
        "priceCurrency": "MAD",
        "price": "<?php echo $currentPrice; ?>",
        "priceValidUntil": "2026-12-31",
        "itemCondition": "https://schema.org/NewCondition",
        "availability": "https://schema.org/InStock"
      }
    }
    </script>
    
    <style>
        /* Reuse styles from index.php */
        .glass-nav {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        .variant-option {
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
        }
        .variant-option.selected {
            border-color: #D4AF37;
            box-shadow: 0 0 0 1px #D4AF37;
        }
        .form-input:focus, .form-select:focus {
            border-color: #D4AF37;
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
    </style>
</head>
<body class="font-sans text-brand-black bg-white antialiased">

    <!-- Navbar (Simplified) -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex flex-col items-center">
                        <img src="images/logo_swis_rm.png" alt="Swis Brands Logo" class="h-28 md:h-28 w-auto object-contain">
                    </a>
                </div>
                <div class="hidden md:flex flex-1 justify-center space-x-10 items-center">
                    <a href="/" class="text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium">Accueil</a>
                    <a href="/#collection" class="text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium">Collection</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Product Section -->
    <div class="pt-32 pb-20 max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-12">
            <!-- Image Side -->
            <div class="md:w-1/2">
                <div class="relative rounded-2xl overflow-hidden shadow-lg bg-gray-100 aspect-w-1 aspect-h-1">
                    <!-- 4. Image Optimization: Lazy Loading & Alt -->
                    <img id="main-image" src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?> - Montre Femme Maroc" class="w-full h-full object-cover" loading="lazy">
                </div>
            </div>

            <!-- Details Side -->
            <div class="md:w-1/2 flex flex-col">
                <span class="text-brand-gold font-bold uppercase tracking-widest text-sm mb-2"><?php echo htmlspecialchars($product['category']); ?></span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-brand-black mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
                <h2 class="text-2xl font-arabic text-gray-500 mb-6 text-right" dir="rtl"><?php echo htmlspecialchars($product['arabicName']); ?></h2>
                
                <div class="flex items-center gap-4 mb-8">
                    <span class="text-4xl font-bold text-brand-black"><?php echo $currentPrice; ?> DH</span>
                    <?php if($oldPrice): ?>
                        <span class="text-xl text-gray-400 line-through"><?php echo $oldPrice; ?> DH</span>
                    <?php endif; ?>
                </div>

                <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                    <?php echo $product['description'] ?? "Cette montre incarne l'élégance intemporelle. Dotée d'un mouvement de précision et d'un design sophistiqué, elle est l'accessoire parfait pour la femme moderne."; ?>
                </p>

                <!-- Variants -->
                <?php if (!empty($product['variants'])): ?>
                <div class="mb-8">
                    <label class="block text-sm font-bold uppercase tracking-widest text-brand-black mb-3">Couleur / اللون</label>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach($product['variants'] as $index => $variant): ?>
                            <div onclick="selectVariant(<?php echo $index; ?>)" 
                                 class="variant-option cursor-pointer rounded-lg overflow-hidden w-16 h-16 relative shadow-sm hover:shadow-md <?php echo $index === 0 ? 'selected' : ''; ?>"
                                 data-image="<?php echo $variant['image']; ?>"
                                 data-name="<?php echo $variant['name']; ?>">
                                <img src="<?php echo $variant['image']; ?>" class="w-full h-full object-cover" alt="<?php echo $variant['name']; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Sélectionné: <span id="selected-variant-name" class="font-bold text-brand-black"><?php echo $product['variants'][0]['name']; ?></span></p>
                </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="mt-auto space-y-4">
                    <button onclick="openWhatsApp()" class="w-full bg-green-600 text-white py-4 rounded-lg uppercase tracking-widest font-bold hover:bg-green-700 transition shadow-lg flex items-center justify-center gap-3 text-lg">
                        <i class="fab fa-whatsapp text-2xl"></i>
                        <span>Commander sur WhatsApp</span>
                    </button>
                    <a href="/" class="block text-center text-gray-500 hover:text-brand-black underline text-sm">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-brand-black text-white py-12 border-t-4 border-brand-gold mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; 2026 Swis Brands. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const product = <?php echo json_encode($product); ?>;
        const settings = <?php echo json_encode($settings); ?>;
        let currentVariant = product.variants && product.variants.length > 0 ? product.variants[0] : null;

        function selectVariant(index) {
            const variants = document.querySelectorAll('.variant-option');
            variants.forEach(v => v.classList.remove('selected'));
            variants[index].classList.add('selected');
            
            currentVariant = product.variants[index];
            document.getElementById('main-image').src = currentVariant.image;
            document.getElementById('selected-variant-name').textContent = currentVariant.name;
        }

        function openWhatsApp() {
            const phone = settings.whatsapp_number;
            const variantName = currentVariant ? currentVariant.name : "Standard";
            const text = `Bonjour, je veux commander: ${product.name} (${variantName}) - <?php echo $currentPrice; ?> DH`;
            window.open(`https://api.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(text)}`, '_blank');
        }
    </script>
</body>
</html>