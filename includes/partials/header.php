<?php
/**
 * Swis Brands - Main Header Partial
 * Includes head section, navigation, and opening body tag
 * 
 * Variables expected:
 * - $pageTitle (string): Page title
 * - $pageDescription (string): Meta description
 * - $pageKeywords (string): Meta keywords
 * - $pageImage (string): OG image URL
 * - $pageUrl (string): Canonical URL
 * - $bodyClass (string): Optional body class
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('SWIS_ROOT')) {
    die('Direct access not allowed');
}

// Get settings
$settings = getSettings();

// Set defaults
$pageTitle = $pageTitle ?? $settings['site_title'] ?? 'Swis Brands';
$pageDescription = $pageDescription ?? 'Découvrez Swis Brands, la référence des montres de luxe pour femmes au Maroc. Élégance, style et qualité supérieure.';
$pageKeywords = $pageKeywords ?? 'montres femmes, luxe, maroc, swis brands, mode, accessoires';
$pageImage = $pageImage ?? SITE_URL . '/images/logo_swis_rm.png';
$pageUrl = $pageUrl ?? SITE_URL . $_SERVER['REQUEST_URI'];
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo sanitize($pageTitle); ?></title>
    <meta name="description" content="<?php echo sanitize($pageDescription); ?>">
    <meta name="keywords" content="<?php echo sanitize($pageKeywords); ?>">
    <link rel="canonical" href="<?php echo sanitize($pageUrl); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo sanitize($pageTitle); ?>">
    <meta property="og:description" content="<?php echo sanitize($pageDescription); ?>">
    <meta property="og:image" content="<?php echo sanitize($pageImage); ?>">
    <meta property="og:url" content="<?php echo sanitize($pageUrl); ?>">
    <meta property="og:site_name" content="Swis Brands">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo sanitize($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo sanitize($pageDescription); ?>">
    <meta name="twitter:image" content="<?php echo sanitize($pageImage); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo_swis_rm.png">
    <link rel="apple-touch-icon" href="/images/logo_swis_rm.png">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Cairo:wght@300;400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind Config -->
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
    
    <!-- Custom Styles -->
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 4px; }
        
        /* Hide scrollbar but allow scroll */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Safe area for iOS */
        .safe-bottom { padding-bottom: max(12px, env(safe-area-inset-bottom)); }
        
        /* Smooth scroll */
        html { scroll-behavior: smooth; scroll-padding-top: 100px; }
        
        /* Animations */
        .fade-in { animation: fadeIn 0.8s ease-in; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Glass navigation */
        .glass-nav {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        /* Product cards */
        .product-card { transition: all 0.4s ease; }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        }
        
        /* Modal styles */
        .modal {
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            pointer-events: none;
        }
        .modal.active { opacity: 1; pointer-events: auto; }
        .modal-content {
            transform: scale(0.95);
            transition: transform 0.3s ease-in-out;
        }
        .modal.active .modal-content { transform: scale(1); }
        
        /* Variant selection */
        .variant-option {
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .variant-option.selected {
            border-color: #D4AF37 !important;
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.3);
        }
        
        /* Badge animation */
        .badge-pulse { animation: pulse-badge 2s infinite; }
        @keyframes pulse-badge {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 6px rgba(212, 175, 55, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
        }
        
        /* Form inputs */
        .form-input:focus, .form-select:focus {
            border-color: #D4AF37;
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
        
        /* Typography */
        h1, h2, h3, h4 { letter-spacing: -0.02em; }
        .tracking-widest { letter-spacing: 0.15em; }
    </style>
    
    <!-- Tracking Pixels (Facebook + TikTok) -->
    <?php echo renderPixels(); ?>
</head>
<body class="font-sans text-brand-black bg-white antialiased <?php echo sanitize($bodyClass); ?>">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-2xl focus:outline-none" onclick="toggleMobileMenu()" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex flex-col items-center">
                        <img src="/images/logo_swis_rm.png" id="logo-img-nav" alt="Swis Brands Logo" class="h-28 md:h-28 w-auto object-contain">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex flex-1 justify-center space-x-10 items-center">
                    <a href="/#home" class="text-xs lg:text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium relative group">
                        Accueil
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-gold transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/#new-arrivals" class="text-xs lg:text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium relative group">
                        Nouveautés
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-gold transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/#new-year" class="text-xs lg:text-sm uppercase tracking-widest text-brand-red hover:text-brand-black transition font-bold relative group">
                        Nouvel An 2026
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/#promo-packs" class="text-xs lg:text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium relative group">
                        Packs Promo
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-gold transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/#collection" class="text-xs lg:text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium relative group">
                        Collection
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-gold transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>

                <!-- Cart Icon -->
                <div class="flex items-center space-x-6">
                    <button class="relative hover:text-brand-gold transition" onclick="toggleCart()" aria-label="Panier">
                        <i class="fas fa-shopping-bag text-xl"></i>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-brand-gold text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center transform scale-0 transition-transform duration-200">0</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t p-4 absolute w-full shadow-lg">
            <a href="/#home" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100">Accueil <span class="font-arabic float-right text-gray-400 text-xs">الرئيسية</span></a>
            <a href="/#new-arrivals" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100 text-brand-gold font-bold">Nouveautés <span class="font-arabic float-right text-brand-gold text-xs">وصل حديثاً</span></a>
            <a href="/#new-year" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100 text-brand-red font-bold">Nouvel An 2026 <span class="font-arabic float-right text-brand-red text-xs">عروض رأس السنة</span></a>
            <a href="/#promo-packs" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100">Packs Promo <span class="font-arabic float-right text-gray-400 text-xs">تخفيضات</span></a>
            <a href="/#collection" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100">Collection <span class="font-arabic float-right text-gray-400 text-xs">التشكيلة</span></a>
        </div>
    </nav>
