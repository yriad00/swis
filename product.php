<?php
/**
 * Swis Brands - Product Detail Page
 * Individual product page with SEO optimization
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Initialize application
require_once __DIR__ . '/includes/bootstrap.php';

// Get product ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    http_response_code(404);
    echo "Produit non trouvé / Product not found";
    exit;
}

// Find product
$result = findProductById($id);

if (!$result) {
    http_response_code(404);
    echo "Produit non trouvé / Product not found";
    exit;
}

$product = $result['product'];
$type = $result['type'];
$settings = getSettings();

// Prepare Dynamic Meta Data
$pageTitle = sanitize($product['name']) . " - " . sanitize($settings['site_title'] ?? 'Swis Brands');
$pageDescription = "Achetez " . sanitize($product['name']) . ". " . ($product['description'] ?? "Montre de luxe pour femme au Maroc. Qualité premium et livraison gratuite.");
$pageKeywords = "montre femme, " . sanitize($product['name']) . ", " . sanitize($product['category']) . ", luxe maroc";

// Handle pricing
$currentPrice = ($type === 'pack') ? ($product['newPrice'] ?? $product['price']) : $product['price'];
$oldPrice = $product['oldPrice'] ?? null;

// Build image URL
$pageImage = getImageUrl($product['image']);
$pageUrl = SITE_URL . '/product.php?id=' . $id;

// Get tracking configuration for ViewContent event
$tracking = getTrackingConfig();
$viewContentEvent = fbPixelViewContent($id, $product['name'], (float)$currentPrice);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="<?php echo $pageKeywords; ?>">
    <link rel="canonical" href="<?php echo sanitize($pageUrl); ?>">
    
    <!-- Open Graph -->
    <meta property="og:type" content="product">
    <meta property="og:title" content="<?php echo $pageTitle; ?>">
    <meta property="og:description" content="<?php echo $pageDescription; ?>">
    <meta property="og:image" content="<?php echo sanitize($pageImage); ?>">
    <meta property="og:url" content="<?php echo sanitize($pageUrl); ?>">
    <meta property="og:site_name" content="Swis Brands">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $pageTitle; ?>">
    <meta name="twitter:description" content="<?php echo $pageDescription; ?>">
    <meta name="twitter:image" content="<?php echo sanitize($pageImage); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo_swis_rm.png">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

    <!-- Structured Data (Schema.org) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "<?php echo sanitize($product['name']); ?>",
        "image": ["<?php echo sanitize($pageImage); ?>"],
        "description": "<?php echo sanitize($pageDescription); ?>",
        "sku": "<?php echo $product['id']; ?>",
        "brand": {
            "@type": "Brand",
            "name": "Swis Brands"
        },
        "offers": {
            "@type": "Offer",
            "url": "<?php echo sanitize($pageUrl); ?>",
            "priceCurrency": "MAD",
            "price": "<?php echo $currentPrice; ?>",
            "priceValidUntil": "<?php echo date('Y-12-31'); ?>",
            "itemCondition": "https://schema.org/NewCondition",
            "availability": "https://schema.org/InStock"
        }
    }
    </script>
    
    <style>
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
    
    <!-- Tracking Pixels -->
    <?php echo renderPixels(); ?>
</head>
<body class="font-sans text-brand-black bg-white antialiased">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex flex-col items-center">
                        <img src="/images/logo_swis_rm.png" alt="Swis Brands Logo" class="h-28 md:h-28 w-auto object-contain">
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
                    <img id="main-image" src="<?php echo sanitize($product['image']); ?>" 
                         alt="<?php echo sanitize($product['name']); ?> - Montre Femme Maroc" 
                         class="w-full h-full object-cover" loading="lazy">
                </div>
                
                <?php if (!empty($product['gallery'])): ?>
                <div class="flex gap-2 mt-4 overflow-x-auto">
                    <div class="w-20 h-20 rounded-lg overflow-hidden cursor-pointer border-2 border-brand-gold flex-shrink-0" onclick="changeMainImage('<?php echo sanitize($product['image']); ?>')">
                        <img src="<?php echo sanitize($product['image']); ?>" class="w-full h-full object-cover" alt="Main">
                    </div>
                    <?php foreach($product['gallery'] as $galleryImg): ?>
                    <div class="w-20 h-20 rounded-lg overflow-hidden cursor-pointer border-2 border-transparent hover:border-brand-gold flex-shrink-0" onclick="changeMainImage('<?php echo sanitize($galleryImg); ?>')">
                        <img src="<?php echo sanitize($galleryImg); ?>" class="w-full h-full object-cover" alt="Gallery">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Details Side -->
            <div class="md:w-1/2 flex flex-col">
                <span class="text-brand-gold font-bold uppercase tracking-widest text-sm mb-2"><?php echo sanitize($product['category']); ?></span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-brand-black mb-2"><?php echo sanitize($product['name']); ?></h1>
                <h2 class="text-2xl font-arabic text-gray-500 mb-6 text-right" dir="rtl"><?php echo sanitize($product['arabicName']); ?></h2>
                
                <div class="flex items-center gap-4 mb-8">
                    <span class="text-4xl font-bold text-brand-black"><?php echo $currentPrice; ?> <?php echo sanitize($settings['currency'] ?? 'DH'); ?></span>
                    <?php if($oldPrice): ?>
                        <span class="text-xl text-gray-400 line-through"><?php echo $oldPrice; ?> <?php echo sanitize($settings['currency'] ?? 'DH'); ?></span>
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
                                 data-image="<?php echo sanitize($variant['image']); ?>"
                                 data-name="<?php echo sanitize($variant['name']); ?>">
                                <img src="<?php echo sanitize($variant['image']); ?>" class="w-full h-full object-cover" alt="<?php echo sanitize($variant['name']); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Sélectionné: <span id="selected-variant-name" class="font-bold text-brand-black"><?php echo sanitize($product['variants'][0]['name']); ?></span></p>
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
                
                <!-- Features -->
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>En Stock</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="fas fa-truck text-brand-gold"></i>
                        <span>Livraison Gratuite</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="fas fa-shield-alt text-brand-gold"></i>
                        <span>Qualité Garantie</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="fas fa-hand-holding-usd text-brand-gold"></i>
                        <span>Paiement à la Livraison</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-brand-black text-white py-12 border-t-4 border-brand-gold mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> Swis Brands. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const product = <?php echo safeJsonEncode($product); ?>;
        const settings = {
            whatsappNumber: '<?php echo sanitize($settings['whatsapp_number'] ?? ''); ?>',
            currency: '<?php echo sanitize($settings['currency'] ?? 'DH'); ?>'
        };
        let currentVariant = product.variants && product.variants.length > 0 ? product.variants[0] : null;

        function selectVariant(index) {
            const variants = document.querySelectorAll('.variant-option');
            variants.forEach(v => v.classList.remove('selected'));
            variants[index].classList.add('selected');
            
            currentVariant = product.variants[index];
            document.getElementById('main-image').src = currentVariant.image;
            document.getElementById('selected-variant-name').textContent = currentVariant.name;
        }

        function changeMainImage(src) {
            document.getElementById('main-image').src = src;
        }

        function openWhatsApp() {
            const phone = settings.whatsappNumber;
            const variantName = currentVariant ? currentVariant.name : "Standard";
            const text = `Bonjour, je veux commander: ${product.name} (${variantName}) - <?php echo $currentPrice; ?> ${settings.currency}`;
            window.open(`https://api.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(text)}`, '_blank');
        }

        // Track ViewContent event
        <?php if ($viewContentEvent): ?>
        <?php echo $viewContentEvent; ?>
        <?php endif; ?>
    </script>
    
    <!-- Custom Body Scripts -->
    <?php echo renderBodyScripts(); ?>
</body>
</html>
