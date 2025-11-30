<?php
// Load settings with error handling
$settingsFile = 'data/settings.json';
if (!file_exists($settingsFile)) {
    die('Error: Settings file not found. Please ensure data/settings.json exists.');
}
$settingsJson = file_get_contents($settingsFile);
$settings = json_decode($settingsJson, true);
if ($settings === null) {
    die('Error: Invalid settings.json format.');
}

// Load products with error handling
$productsFile = 'data/products.json';
if (!file_exists($productsFile)) {
    die('Error: Products file not found. Please ensure data/products.json exists.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez Swis Brands, la référence des montres de luxe pour femmes au Maroc. Élégance, style et qualité supérieure. Livraison partout au Maroc.">
    <meta name="keywords" content="montres femmes, luxe, maroc, swis brands, mode, accessoires, cadeaux, casablanca, rabat, marrakech">
    <meta property="og:title" content="<?php echo htmlspecialchars($settings['site_title']); ?>">
    <meta property="og:description" content="Élégance et raffinement pour la femme marocaine moderne. Découvrez notre nouvelle collection.">
    <meta property="og:image" content="images/logo_swis_rm.png">
    <meta property="og:url" content="https://swisbrands.ma">
    <title><?php echo htmlspecialchars($settings['site_title']); ?></title>
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
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #D4AF37;
            border-radius: 4px;
        }
        
        /* Hide scrollbar but allow scroll */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Safe area for iOS */
        .safe-bottom {
            padding-bottom: max(12px, env(safe-area-inset-bottom));
        }
        
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px; /* Fix for fixed navbar covering content */
        }

        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.90); /* More transparency */
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1); /* Subtle gold border */
        }

        .product-card {
            transition: all 0.4s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        }
        
        /* Bundle Composer Styles */
        #bundle-modal .bundle-slot.active {
            border-color: #D4AF37 !important;
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.3);
        }
        
        #bundle-modal .variant-card {
            transition: all 0.2s ease;
        }
        #bundle-modal .variant-card:active {
            transform: scale(0.95);
        }
        #bundle-modal .variant-card.selected {
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.3);
        }
        
        /* Modal Transitions */
        .modal {
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            pointer-events: none;
        }
        .modal.active {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-content {
            transform: scale(0.95);
            transition: transform 0.3s ease-in-out;
        }
        .modal.active .modal-content {
            transform: scale(1);
        }

        /* Variant Selection Styles */
        .variant-option {
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .variant-option.selected {
            border-color: #D4AF37 !important;
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.3);
        }
        
        /* New Badge Animation */
        .badge-pulse {
            animation: pulse-badge 2s infinite;
        }
        @keyframes pulse-badge {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 6px rgba(212, 175, 55, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
        }

        /* Input Styles */
        .form-input:focus, .form-select:focus {
            border-color: #D4AF37;
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
        
        /* Luxury Typography Tweaks */
        h1, h2, h3, h4 {
            letter-spacing: -0.02em;
        }
        .tracking-widest {
            letter-spacing: 0.15em;
        }
    </style>
</head>
<body class="font-sans text-brand-black bg-white antialiased">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-2xl focus:outline-none" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="#" class="flex flex-col items-center">
                        <img src="images/logo_swis_rm.png" id="logo-img-nav" alt="Swis Brands Logo" class="h-28 md:h-28 w-auto object-contain">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex flex-1 justify-center space-x-10 items-center">
                    <a href="#home" class="text-xs lg:text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium relative group">
                        Accueil
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-gold transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#new-arrivals" class="text-xs lg:text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium relative group">
                        Nouveautés
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-gold transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#new-year" class="text-xs lg:text-sm uppercase tracking-widest text-brand-red hover:text-brand-black transition font-bold relative group">
                        Nouvel An 2026
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#promo-packs" class="text-xs lg:text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium relative group">
                        Packs Promo
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-gold transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#collection" class="text-xs lg:text-sm uppercase tracking-widest hover:text-brand-gold transition font-medium relative group">
                        Collection
                        <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-brand-gold transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>

                <!-- Icons -->
                <div class="flex items-center space-x-6">
                    <button class="relative hover:text-brand-gold transition" onclick="toggleCart()">
                        <i class="fas fa-shopping-bag text-xl"></i>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-brand-gold text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center transform scale-0 transition-transform duration-200">0</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t p-4 absolute w-full shadow-lg">
            <a href="#home" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100">Accueil <span class="font-arabic float-right text-gray-400 text-xs">الرئيسية</span></a>
            <a href="#new-arrivals" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100 text-brand-gold font-bold">Nouveautés <span class="font-arabic float-right text-brand-gold text-xs">وصل حديثاً</span></a>
            <a href="#new-year" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100 text-brand-red font-bold">Nouvel An 2026 <span class="font-arabic float-right text-brand-red text-xs">عروض رأس السنة</span></a>
            <a href="#promo-packs" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100">Packs Promo <span class="font-arabic float-right text-gray-400 text-xs">تخفيضات</span></a>
            <a href="#collection" onclick="toggleMobileMenu()" class="block py-3 text-sm uppercase tracking-widest border-b border-gray-100">Collection <span class="font-arabic float-right text-gray-400 text-xs">التشكيلة</span></a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="images/IMG-20251118-WA0073.jpg" alt="Luxury Watch" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        </div>

        <div class="relative z-10 text-center text-white px-4 fade-in max-w-3xl mx-auto">
            <p class="text-brand-gold uppercase tracking-[0.3em] mb-4 text-sm font-semibold">Nouvelle Collection 2026</p>
            <h1 class="text-5xl md:text-7xl font-serif font-bold mb-2">JUST WATCH ME</h1>
            <h2 class="text-3xl md:text-4xl font-arabic font-bold mb-6 text-gray-100">أناقتك، في كل دقيقة</h2>
            <p class="text-lg font-light mb-10 text-gray-200">Découvrez l'élégance du savoir-faire marocain allié à la précision suisse.</p>
            <a href="#collection" class="inline-block bg-white text-brand-black px-10 py-4 uppercase tracking-widest font-bold hover:bg-brand-gold hover:text-white transition duration-300">
                Acheter Maintenant
            </a>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16 bg-brand-light">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6 bg-white shadow-sm rounded-lg hover:shadow-md transition">
                <i class="fas fa-shipping-fast text-4xl text-brand-gold mb-4"></i>
                <h3 class="font-serif text-xl mb-1">Livraison Gratuite</h3>
                <p class="font-arabic text-brand-black text-lg mb-2">توصيل مجاني</p>
            </div>
            <div class="p-6 bg-white shadow-sm rounded-lg hover:shadow-md transition">
                <i class="fas fa-hand-holding-dollar text-4xl text-brand-gold mb-4"></i>
                <h3 class="font-serif text-xl mb-1">Paiement à la Livraison</h3>
                <p class="font-arabic text-brand-black text-lg mb-2">الدفع عند الاستلام</p>
            </div>
            <div class="p-6 bg-white shadow-sm rounded-lg hover:shadow-md transition">
                <i class="fas fa-gem text-4xl text-brand-gold mb-4"></i>
                <h3 class="font-serif text-xl mb-1">Qualité Premium</h3>
                <p class="font-arabic text-brand-black text-lg mb-2">جودة عالية</p>
            </div>
        </div>
    </section>

    <!-- SECTION: NEW ARRIVALS (NOUVEAUTÉS) -->
    <section id="new-arrivals" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-brand-gold font-bold tracking-widest uppercase text-sm">Sal3a Jdida</span>
                <h2 class="text-4xl font-serif font-bold mb-2">Nouveautés</h2>
                <p class="font-arabic text-xl text-gray-500">وصل حديثاً</p>
                <div class="h-1 w-20 bg-brand-black mx-auto mt-4"></div>
            </div>

            <div id="new-arrivals-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Rendered by JS -->
            </div>
        </div>
    </section>

    <!-- SECTION: NEW YEAR COLLECTION (RASS SANA) -->
    <section id="new-year" class="py-20 bg-brand-black text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-gold opacity-10 rounded-full transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-brand-gold opacity-5 rounded-full transform -translate-x-1/2 translate-y-1/2"></div>
        
        <!-- Floating confetti/stars decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-10 text-brand-gold text-2xl animate-pulse">✦</div>
            <div class="absolute top-20 right-20 text-brand-gold text-xl animate-bounce" style="animation-delay: 0.5s">★</div>
            <div class="absolute bottom-32 left-1/4 text-brand-gold text-lg animate-pulse" style="animation-delay: 1s">✧</div>
            <div class="absolute top-1/3 right-10 text-brand-gold text-2xl animate-bounce" style="animation-delay: 0.3s">✦</div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                <div class="md:w-1/2 text-center md:text-left">
                    <!-- New Year Badge -->
                    <div class="inline-flex items-center gap-2 py-2 px-4 bg-gradient-to-r from-brand-gold/20 to-brand-red/20 border border-brand-gold text-brand-gold text-sm tracking-widest uppercase mb-4 rounded-full">
                        <span class="animate-pulse">🎆</span>
                        <span>عروض رأس السنة 2026</span>
                        <span class="animate-pulse">🎆</span>
                    </div>
                    
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-serif font-bold mb-3 text-brand-goldLight leading-tight">
                        Nouvel An 2026
                        <span class="block text-white text-2xl md:text-3xl mt-2">أناقة لا تُنسى</span>
                    </h2>
                    
                    <p class="text-gray-300 mb-2 leading-relaxed text-sm md:text-base">
                        Le compte à rebours a commencé ! Profitez de nos offres exceptionnelles avant la nouvelle année.
                    </p>
                    <p class="font-arabic text-brand-gold text-lg md:text-xl mb-6">
                        🎁 هدايا حصرية و عروض ما كتتعاودش 🎁
                    </p>
                    
                    <!-- FUNCTIONAL COUNTDOWN TO JAN 1, 2026 -->
                    <div class="flex justify-center md:justify-start gap-2 md:gap-4 mb-6">
                        <div class="text-center">
                            <div id="days" class="w-14 h-14 md:w-16 md:h-16 rounded-xl bg-gradient-to-b from-white/20 to-white/5 backdrop-blur border border-brand-gold/30 flex items-center justify-center text-xl md:text-2xl font-bold text-brand-gold shadow-lg">00</div>
                            <span class="text-[10px] md:text-xs text-gray-400 mt-1 block">Jours</span>
                        </div>
                        <div class="text-brand-gold text-2xl font-bold self-center mb-4">:</div>
                        <div class="text-center">
                            <div id="hours" class="w-14 h-14 md:w-16 md:h-16 rounded-xl bg-gradient-to-b from-white/20 to-white/5 backdrop-blur border border-brand-gold/30 flex items-center justify-center text-xl md:text-2xl font-bold text-brand-gold shadow-lg">00</div>
                            <span class="text-[10px] md:text-xs text-gray-400 mt-1 block">Heures</span>
                        </div>
                        <div class="text-brand-gold text-2xl font-bold self-center mb-4">:</div>
                        <div class="text-center">
                            <div id="minutes" class="w-14 h-14 md:w-16 md:h-16 rounded-xl bg-gradient-to-b from-white/20 to-white/5 backdrop-blur border border-brand-gold/30 flex items-center justify-center text-xl md:text-2xl font-bold text-brand-gold shadow-lg">00</div>
                            <span class="text-[10px] md:text-xs text-gray-400 mt-1 block">Min</span>
                        </div>
                        <div class="text-brand-gold text-2xl font-bold self-center mb-4">:</div>
                        <div class="text-center">
                            <div id="seconds" class="w-14 h-14 md:w-16 md:h-16 rounded-xl bg-gradient-to-b from-white/20 to-white/5 backdrop-blur border border-brand-gold/30 flex items-center justify-center text-xl md:text-2xl font-bold text-white shadow-lg animate-pulse">00</div>
                            <span class="text-[10px] md:text-xs text-gray-400 mt-1 block">Sec</span>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                        <a href="#promo-packs" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-brand-gold to-yellow-500 text-brand-black px-8 py-4 uppercase tracking-widest font-bold hover:from-white hover:to-white transition duration-300 rounded-lg shadow-[0_0_30px_rgba(212,175,55,0.5)]">
                            <span>🎁</span>
                            <span>Voir les Promos</span>
                        </a>
                        <a href="#collection" class="inline-flex items-center justify-center gap-2 border-2 border-brand-gold text-brand-gold px-8 py-4 uppercase tracking-widest font-bold hover:bg-brand-gold hover:text-black transition duration-300 rounded-lg">
                            <span class="font-arabic">شوفي التشكيلة ✨</span>
                        </a>
                    </div>
                </div>
                
                <!-- New Year Image -->
                <div class="md:w-1/2 relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-brand-gold/20 to-brand-red/20 rounded-2xl blur-xl"></div>
                    <div class="absolute inset-0 border-2 border-brand-gold/50 transform translate-x-3 translate-y-3 rounded-2xl"></div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl shadow-brand-gold/20">
                        <img src="/images/IMG-20251111-WA0005.jpg" alt="Swis Brands New Year 2026" class="w-full object-cover h-[350px] md:h-[500px]">
                        <!-- Overlay badge -->
                        <div class="absolute top-4 right-4 bg-brand-red text-white px-4 py-2 rounded-full text-sm font-bold animate-bounce">
                            🔥 -50%
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 text-center">
                            <p class="font-arabic text-brand-gold text-lg">✨ استعدي لسنة جديدة بإطلالة راقية ✨</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: PROMO PACKS -->
    <section id="promo-packs" class="py-20 bg-gray-50 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-brand-red font-bold tracking-widest uppercase text-sm">Offres Spéciales</span>
                <h2 class="text-4xl font-serif font-bold mb-2">Coffrets Cadeaux</h2>
                <p class="font-arabic text-xl text-gray-500">باكات و هدايا حصرية</p>
                <div class="h-1 w-20 bg-brand-red mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="packs-container">
                <!-- Packs rendered by JS -->
            </div>
        </div>
    </section>

    <!-- Main Collection -->
    <section id="collection" class="py-20 max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-serif font-bold mb-2">Notre Collection</h2>
            <p class="font-arabic text-xl text-gray-500 mb-4">اكتشفي تشكيلتنا الجديدة</p>
            <div class="h-1 w-20 bg-brand-gold mx-auto"></div>
        </div>

        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-8 mb-12">
            <!-- Products will be inserted here by JS -->
        </div>

        <div class="text-center">
            <button id="load-more-btn" class="border-2 border-brand-black text-brand-black px-10 py-3 uppercase tracking-widest font-bold hover:bg-brand-black hover:text-white transition duration-300">
                Voir Plus <span class="font-arabic ml-2">المزيد</span>
            </button>
        </div>
    </section>

    <!-- Customer Reviews (Testimonials) -->
    <section class="py-20 bg-brand-black text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-brand-gold font-bold tracking-widest uppercase text-sm">Témoignages</span>
                <h2 class="text-4xl font-serif font-bold mb-2 text-white">Nos Clientes Disent</h2>
                <p class="font-arabic text-xl text-gray-400">آراء زبوناتنا الوفيات</p>
                <div class="h-1 w-20 bg-brand-gold mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Review 1 -->
                <div class="bg-white/5 backdrop-blur-sm p-8 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300 group">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://ui-avatars.com/api/?name=Salma+Benani&background=D4AF37&color=fff" alt="Salma" class="w-12 h-12 rounded-full border-2 border-brand-gold">
                        <div>
                            <h4 class="font-bold text-lg text-white">Salma Benani</h4>
                            <div class="text-brand-gold text-xs">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic leading-relaxed">
                        "Reçu aujourd'hui ! Franchement la qualité est incroyable, <span class="text-brand-gold">sa3a nadiya bzaf</span> ou katban luxe f l yed. Merci pour le service ❤️"
                    </p>
                </div>

                <!-- Review 2 -->
                <div class="bg-white/5 backdrop-blur-sm p-8 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300 group">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://ui-avatars.com/api/?name=Imane+Tazi&background=D4AF37&color=fff" alt="Imane" class="w-12 h-12 rounded-full border-2 border-brand-gold">
                        <div>
                            <h4 class="font-bold text-lg text-white">Imane Tazi</h4>
                            <div class="text-brand-gold text-xs">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic leading-relaxed">
                        "Commande wslatni f 24h, tbarkallah 3likom. <span class="text-brand-gold">Livreur drif</span> ou sa3a magnifique. Je recommande à 100% !"
                    </p>
                </div>

                <!-- Review 3 -->
                <div class="bg-white/5 backdrop-blur-sm p-8 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300 group">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://ui-avatars.com/api/?name=Houda+Idrissi&background=D4AF37&color=fff" alt="Houda" class="w-12 h-12 rounded-full border-2 border-brand-gold">
                        <div>
                            <h4 class="font-bold text-lg text-white">Houda El Idrissi</h4>
                            <div class="text-brand-gold text-xs">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic leading-relaxed">
                        "Khditha cadeau l khti, 7ma9at 3liha! <span class="text-brand-gold">L'emballage est très classe</span>. Merci Swis Brands pour le professionnalisme 🎁"
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Shopping Cart Sidebar -->
    <div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full md:w-96 bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="h-full flex flex-col">
            <div class="p-6 border-b flex justify-between items-center bg-brand-black text-white">
                <div>
                    <h2 class="font-serif text-xl">Mon Panier</h2>
                    <p class="font-arabic text-xs text-gray-400">سلة المشتريات</p>
                </div>
                <button onclick="toggleCart()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4"></div>
            <div class="p-6 border-t bg-gray-50">
                <div class="flex justify-between mb-4 font-bold text-lg">
                    <span>Total / المجموع</span>
                    <span id="cart-total">0.00 MAD</span>
                </div>
                <!-- Changed onclick to openCheckoutModal -->
                <button onclick="openCheckoutModal()" class="w-full bg-green-600 text-white py-4 uppercase tracking-widest font-bold hover:bg-green-700 transition flex items-center justify-center gap-2 rounded-lg shadow-lg">
                    <i class="fab fa-whatsapp text-2xl"></i> 
                    <div class="text-left leading-tight">
                        <span class="block text-xs">Commander via</span>
                        <span class="block font-bold">WhatsApp</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
    <div id="cart-overlay" onclick="toggleCart()" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden fade-in"></div>

    <!-- PRODUCT DETAILS MODAL (POPUP) -->
    <div id="product-modal" class="modal fixed inset-0 z-[60] flex items-center justify-center p-3 md:p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-80" onclick="closeModal()"></div>
        
        <!-- Modal Content - Centered with rounded corners -->
        <div class="modal-content bg-white w-full max-h-[88vh] md:max-h-[85vh] md:w-[90%] md:max-w-4xl rounded-2xl shadow-2xl overflow-hidden relative z-10 flex flex-col md:flex-row">
            
            <!-- Close Button -->
            <button onclick="closeModal()" class="absolute top-3 right-3 z-30 bg-white/90 text-gray-600 rounded-full w-9 h-9 flex items-center justify-center shadow-lg hover:text-brand-red transition">
                <i class="fas fa-times text-lg"></i>
            </button>

            <!-- Image Side - 45vh on mobile, proper height on desktop -->
            <div class="relative w-full h-[42vh] md:h-auto md:w-1/2 bg-gray-100 flex-shrink-0">
                <img id="modal-image" src="" alt="Watch Detail" class="w-full h-full object-cover transition duration-500">
                
                <!-- Navigation Arrows -->
                <button id="modal-prev-btn" onclick="navigateModalImage(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-brand-black w-9 h-9 rounded-full shadow-lg flex items-center justify-center z-10 hidden active:scale-95 transition">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="modal-next-btn" onclick="navigateModalImage(1)" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-brand-black w-9 h-9 rounded-full shadow-lg flex items-center justify-center z-10 hidden active:scale-95 transition">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Image Counter -->
                <div id="modal-image-counter" class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/70 text-white px-3 py-1 text-xs font-bold rounded-full hidden">
                    <span id="modal-current-index">1</span> / <span id="modal-total-images">1</span>
                </div>
                
                <!-- Gallery Mode Toggle -->
                <button id="modal-gallery-toggle" onclick="toggleModalGalleryMode()" class="absolute top-3 left-3 bg-white/90 hover:bg-white text-brand-black px-3 py-1.5 rounded-full shadow text-xs font-bold hidden">
                    <i class="fas fa-images mr-1"></i><span id="gallery-toggle-text">Toutes</span>
                </button>
            </div>

            <!-- Details Side -->
            <div class="flex-1 md:w-1/2 flex flex-col bg-white overflow-hidden">
                <div class="flex-1 overflow-y-auto p-4 md:p-6">
                    <span id="modal-category" class="text-brand-gold font-bold uppercase tracking-widest text-[10px] md:text-xs mb-1 block">Category</span>
                    <h2 id="modal-title" class="text-lg md:text-2xl font-serif font-bold text-brand-black mb-0.5">Product Title</h2>
                    <h3 id="modal-arabic" class="text-sm md:text-lg font-arabic text-gray-500 mb-2">اسم المنتج</h3>

                    <!-- VARIANT SELECTION (COLOR) -->
                    <div id="variant-section" class="mb-3 hidden">
                        <label class="block text-[10px] md:text-xs font-bold uppercase tracking-widest text-brand-black mb-2">
                            Couleur / اللون: <span id="selected-variant-name" class="text-brand-gold ml-1"></span>
                        </label>
                        <div id="variant-options" class="flex flex-wrap gap-2">
                            <!-- Variants injected here by JS -->
                        </div>
                    </div>

                    <!-- Price & Quantity Row -->
                    <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                        <div class="flex items-center gap-2">
                            <span id="modal-price" class="text-2xl md:text-3xl font-bold text-brand-black">000 DH</span>
                            <span id="modal-old-price" class="text-sm md:text-lg text-gray-400 line-through hidden">000 DH</span>
                        </div>
                        <div class="flex items-center border border-gray-200 rounded-md">
                            <button onclick="adjustModalQty(-1)" class="px-3 py-1.5 hover:bg-gray-50 text-gray-600 transition">-</button>
                            <span id="modal-qty" class="px-3 py-1.5 font-bold text-brand-black min-w-[2rem] text-center text-sm">1</span>
                            <button onclick="adjustModalQty(1)" class="px-3 py-1.5 hover:bg-gray-50 text-gray-600 transition">+</button>
                        </div>
                    </div>
                </div>

                <!-- Actions - Fixed at bottom -->
                <div class="p-4 pt-2 border-t bg-white">
                    <div class="flex gap-3">
                        <button id="modal-add-btn" class="flex-1 bg-brand-black text-white py-3.5 rounded-lg uppercase tracking-widest font-bold hover:bg-brand-gold transition shadow-lg text-xs md:text-sm">
                            Ajouter au Panier
                        </button>
                        <button id="modal-buy-now-btn" class="flex-none bg-green-100 text-green-600 px-4 py-3.5 rounded-lg hover:bg-green-200 transition flex items-center justify-center" title="Acheter maintenant">
                            <i class="fab fa-whatsapp text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="mt-2 flex items-center justify-center gap-4 text-[10px] md:text-xs text-gray-500">
                        <span class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-1"></i> En Stock</span>
                        <span class="flex items-center"><i class="fas fa-truck mr-1"></i> Livraison 24h/48h</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- *** CHECKOUT MODAL (NEW) *** -->
    <div id="checkout-modal" class="modal fixed inset-0 z-[70] flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-70" onclick="closeCheckoutModal()"></div>
        <div class="modal-content bg-white w-full max-w-md rounded-2xl shadow-2xl relative z-10 p-8">
            <button onclick="closeCheckoutModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <div class="text-center mb-6">
                <h2 class="font-serif text-2xl font-bold text-brand-black mb-2">Finaliser la Commande</h2>
                <p class="text-gray-500 text-sm font-arabic">أكملي المعلومات للطلب</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Nom Complet / الاسم الكامل</label>
                    <input type="text" id="checkout-name" class="form-input w-full border border-gray-300 rounded-lg p-3 text-sm" placeholder="Votre nom...">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Téléphone / الهاتف</label>
                    <input type="tel" id="checkout-phone" class="form-input w-full border border-gray-300 rounded-lg p-3 text-sm" placeholder="06 XX XX XX XX">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Ville / المدينة</label>
                    <select id="checkout-city" class="form-select w-full border border-gray-300 rounded-lg p-3 text-sm bg-white">
                        <option value="">Choisir une ville...</option>
                        <option value="Casablanca">Casablanca</option>
                        <option value="Rabat">Rabat</option>
                        <option value="Marrakech">Marrakech</option>
                        <option value="Tanger">Tanger</option>
                        <option value="Agadir">Agadir</option>
                        <option value="Fès">Fès</option>
                        <option value="Meknès">Meknès</option>
                        <option value="Oujda">Oujda</option>
                        <option value="Kenitra">Kenitra</option>
                        <option value="Tetouan">Tetouan</option>
                        <option value="Sale">Salé</option>
                        <option value="Temara">Témara</option>
                        <option value="Mohammedia">Mohammedia</option>
                        <option value="El Jadida">El Jadida</option>
                        <option value="Safi">Safi</option>
                        <option value="Beni Mellal">Beni Mellal</option>
                        <option value="Nador">Nador</option>
                        <option value="Autre">Autre / Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Adresse / العنوان</label>
                    <input type="text" id="checkout-address" class="form-input w-full border border-gray-300 rounded-lg p-3 text-sm" placeholder="Ex: Quartier, Rue, N°...">
                </div>
                
                <button onclick="finalizeOrder()" class="w-full bg-green-600 text-white py-4 mt-4 rounded-lg uppercase tracking-widest font-bold hover:bg-green-700 transition shadow-lg flex items-center justify-center gap-2">
                    <span>Confirmer sur WhatsApp</span>
                    <i class="fab fa-whatsapp text-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- *** FULL SCREEN BUNDLE COMPOSER *** -->
    <div id="bundle-modal" class="fixed inset-0 z-[65] bg-white hidden" style="overflow: hidden;">
        
        <!-- Fixed Header -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-brand-black text-white shadow-lg">
            <div class="flex items-center justify-between px-4 py-3">
                <button onclick="closeBundleModal()" class="flex items-center gap-2 text-gray-300 hover:text-white">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text-sm hidden sm:inline">Retour</span>
                </button>
                <div class="text-center">
                    <h2 class="font-serif text-lg font-bold" id="bundle-title">Composez votre Pack</h2>
                    <p class="text-xs text-brand-gold" id="bundle-subtitle">Sélectionnez 2 montres</p>
                </div>
                <div class="w-16"></div>
            </div>
            
            <!-- Compact Slots - Always Visible -->
            <div class="bg-gray-900 px-4 py-3 border-t border-gray-700">
                <div class="flex justify-center items-center gap-3">
                    <!-- Slot 1 Mini -->
                    <div id="slot-1" onclick="activateSlot(1)" class="bundle-slot w-14 h-14 sm:w-16 sm:h-16 border-2 border-dashed border-gray-600 rounded-lg flex items-center justify-center cursor-pointer hover:border-brand-gold transition bg-gray-800 relative overflow-hidden">
                        <div class="placeholder">
                            <i class="fas fa-plus text-gray-500 text-sm"></i>
                        </div>
                        <img src="" class="slot-img absolute inset-0 w-full h-full object-cover hidden">
                        <button onclick="clearSlot(event, 1)" class="clear-btn absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-[10px] hidden items-center justify-center z-10 shadow"><i class="fas fa-times"></i></button>
                        <span class="slot-label absolute bottom-0 left-0 right-0 bg-black/70 text-[9px] text-center text-white py-0.5 truncate px-1"></span>
                    </div>
                    
                    <!-- Plus -->
                    <div class="text-brand-gold text-lg">+</div>
                    
                    <!-- Slot 2 Mini -->
                    <div id="slot-2" onclick="activateSlot(2)" class="bundle-slot w-14 h-14 sm:w-16 sm:h-16 border-2 border-dashed border-gray-600 rounded-lg flex items-center justify-center cursor-pointer hover:border-brand-gold transition bg-gray-800 relative overflow-hidden">
                        <div class="placeholder">
                            <i class="fas fa-plus text-gray-500 text-sm"></i>
                        </div>
                        <img src="" class="slot-img absolute inset-0 w-full h-full object-cover hidden">
                        <button onclick="clearSlot(event, 2)" class="clear-btn absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-[10px] hidden items-center justify-center z-10 shadow"><i class="fas fa-times"></i></button>
                        <span class="slot-label absolute bottom-0 left-0 right-0 bg-black/70 text-[9px] text-center text-white py-0.5 truncate px-1"></span>
                    </div>
                    
                    <!-- Price Badge -->
                    <div class="ml-3 text-right">
                        <div class="text-brand-gold font-bold text-lg" id="bundle-price-display">--</div>
                        <div class="text-[10px] text-gray-400">Prix Pack</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scrollable Content Area -->
        <div class="pt-28 pb-24 h-full overflow-y-auto" id="bundle-scroll-area">
            
            <!-- Product Selection View -->
            <div id="bundle-selection-area" class="px-4 py-4">
                <h3 class="text-xs font-bold uppercase tracking-widest mb-3 text-gray-500">
                    <i class="fas fa-hand-pointer mr-1"></i>
                    Choisissez pour <span id="active-slot-label" class="text-brand-black">Montre 1</span>
                </h3>
                <div id="bundle-products-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <!-- Products injected here -->
                </div>
            </div>
            
            <!-- Variant Selection View -->
            <div id="bundle-variant-area" class="hidden">
                <!-- Back Button -->
                <div class="sticky top-0 bg-white z-10 px-4 py-3 border-b shadow-sm">
                    <button onclick="backToProducts()" class="flex items-center gap-2 text-gray-600 hover:text-brand-black text-sm font-medium">
                        <i class="fas fa-chevron-left"></i> Changer de montre
                    </button>
                </div>
                
                <!-- Two Column Layout on larger screens -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6 p-4">
                    
                    <!-- Left: Image Gallery -->
                    <div class="relative">
                        <!-- Main Image -->
                        <div class="relative aspect-square bg-gray-100 rounded-xl overflow-hidden shadow-lg">
                            <img id="bundle-variant-image" src="" class="w-full h-full object-cover">
                            
                            <!-- Navigation Arrows -->
                            <button id="bundle-prev-btn" onclick="navigateBundleGallery(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-brand-black w-10 h-10 rounded-full shadow-lg flex items-center justify-center z-10 hidden active:scale-95 transition">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button id="bundle-next-btn" onclick="navigateBundleGallery(1)" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-brand-black w-10 h-10 rounded-full shadow-lg flex items-center justify-center z-10 hidden active:scale-95 transition">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            
                            <!-- Image Counter -->
                            <div id="bundle-image-counter" class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/70 text-white px-3 py-1 text-xs rounded-full hidden">
                                <span id="bundle-current-idx">1</span> / <span id="bundle-total-imgs">1</span>
                            </div>
                            
                            <!-- Gallery Mode Toggle -->
                            <button id="bundle-gallery-toggle" onclick="toggleBundleGalleryMode()" class="absolute top-2 left-2 bg-white/90 hover:bg-white text-brand-black px-2 py-1 rounded-full shadow text-xs font-bold hidden">
                                <i class="fas fa-images mr-1"></i><span id="bundle-gallery-toggle-text">Toutes</span>
                            </button>
                        </div>
                        
                        <!-- Gallery Thumbnails -->
                        <div id="bundle-gallery-thumbs" class="mt-3 hidden">
                            <div id="bundle-gallery-grid" class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                                <!-- Gallery thumbs -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: Product Info & Variants -->
                    <div class="mt-3 md:mt-0 flex flex-col">
                        <!-- Product Name -->
                        <h3 id="bundle-variant-product-name" class="font-serif text-lg md:text-2xl font-bold mb-2 md:mb-4"></h3>
                        
                        <!-- Confirm Button - Show early on mobile -->
                        <div class="order-first md:order-last mb-3 md:mb-0 md:mt-4">
                            <button id="confirm-variant-btn" onclick="confirmBundleVariant()" class="w-full bg-brand-gold text-white py-3 md:py-4 rounded-xl uppercase tracking-widest text-xs md:text-sm font-bold hover:bg-brand-black transition shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Sélectionner cette montre
                            </button>
                        </div>
                        
                        <!-- Variant Selection -->
                        <div class="mb-2 md:mb-4">
                            <h4 class="text-[10px] md:text-xs font-bold uppercase tracking-widest mb-2 text-gray-500 flex items-center gap-2">
                                <i class="fas fa-palette text-brand-gold"></i>
                                Choisissez la couleur
                            </h4>
                            <div id="bundle-variant-options" class="flex flex-wrap gap-2">
                                <!-- Variant cards injected here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Fixed Bottom Action Bar -->
        <div class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t shadow-lg px-4 py-3 safe-bottom">
            <button id="add-bundle-btn" onclick="addBundleToCart()" disabled class="w-full bg-gray-300 text-white py-4 rounded-xl uppercase tracking-widest font-bold transition shadow-lg cursor-not-allowed flex items-center justify-center gap-2">
                <i class="fas fa-shopping-bag"></i>
                <span>Sélectionnez 2 montres</span>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-brand-black text-white py-16 border-t-4 border-brand-gold">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
            <div class="flex flex-col items-center md:items-start">
                 <img src="/images/Gemini_Generated_Image_82jog382jog382jo.png" id="logo-img-footer" alt="Swis Brands Logo" class="h-20 w-auto object-contain mb-6 bg-white p-2 rounded">
                <p class="text-gray-400 text-sm leading-relaxed mb-4">
                    Swis Brands est plus qu'une montre. C'est une déclaration d'élégance pour la femme marocaine moderne.
                </p>
            </div>
            <div>
                <h4 class="font-serif text-lg mb-6 text-brand-gold uppercase tracking-widest">Contactez-nous</h4>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li class="flex items-center justify-center md:justify-start hover:text-brand-gold transition cursor-pointer">
                        <i class="fab fa-whatsapp mr-3 text-brand-gold"></i> +212 644-824488
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-serif text-lg mb-6 text-brand-gold uppercase tracking-widest">Suivez-nous</h4>
                <div class="flex space-x-4 justify-center md:justify-start">
                    <a href="#" class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-white transition transform hover:-translate-y-1">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-600 text-xs">
            &copy; 2026 Swis Brands. All rights reserved.
        </div>
    </footer>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-brand-black text-white px-6 py-3 rounded shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none z-[80] flex items-center gap-2 w-max">
        <i class="fas fa-check-circle text-green-500"></i>
        <span>Ajouté au panier!</span>
    </div>

    <script>
        <?php
        $productsJson = file_get_contents($productsFile);
        $data = json_decode($productsJson, true);
        if ($data === null) {
            die('Error: Invalid products.json format.');
        }
        
        // Safely encode data for JavaScript with proper escaping
        $jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE;
        ?>
        // --- DATA ---
        
        // 1. New Arrivals Data
        const newArrivals = <?php echo json_encode($data['newArrivals'] ?? [], $jsonOptions); ?>;

        // 2. Main Collection Data
        const allProducts = <?php echo json_encode($data['allProducts'] ?? [], $jsonOptions); ?>;

        // 3. Packs Data
        const promoPacks = <?php echo json_encode($data['promoPacks'] ?? [], $jsonOptions); ?>;

        // Combined Products for Bundle Logic
        const availableForBundle = [...newArrivals, ...allProducts];

        let cart = JSON.parse(localStorage.getItem('swisCart')) || [];
        let visibleProducts = 4;
        let currentSelectedVariant = null; 
        let currentModalQty = 1;
        
        // Image navigation state
        let modalImages = [];
        let currentImageIndex = 0;
        
        // Current modal product reference
        let currentModalProduct = null;
        let currentModalType = 'collection';

        // Helper function to escape HTML for XSS prevention
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function adjustModalQty(change) {
            currentModalQty += change;
            if (currentModalQty < 1) currentModalQty = 1;
            document.getElementById('modal-qty').textContent = currentModalQty;
        }

        // --- COUNTDOWN TO NEW YEAR 2026 ---
        function startCountdown() {
            // Set target to January 1, 2026 at midnight (Morocco time)
            const endDate = new Date('2026-01-01T00:00:00+01:00');
            
            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = endDate - now;
                
                if (distance < 0) { 
                    // New Year has arrived!
                    clearInterval(timer);
                    document.getElementById("days").innerHTML = "🎉";
                    document.getElementById("hours").innerHTML = "🎊";
                    document.getElementById("minutes").innerHTML = "🎆";
                    document.getElementById("seconds").innerHTML = "✨";
                    return; 
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
                document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
                document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
                document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
            }, 1000);
        }

        // --- BUNDLE LOGIC ---
        let currentBundle = {
            id: null,
            type: null, // pack1, pack2, pack3
            slot1: null,
            slot2: null,
            price: 0,
            name: ""
        };
        let activeSlot = 1;
        
        // Bundle variant selection state
        let pendingBundleProduct = null;
        let pendingBundleVariantIndex = 0;
        let pendingBundleSlot = null;
        let bundleGalleryImages = [];
        let bundleGalleryIndex = 0;
        
        // Bundle gallery mode tracking
        let bundleGalleryMode = 'variant'; // 'variant' or 'all'
        let bundleAllProductImages = [];
        let bundleVariantImages = [];

        function openBundleModal(packId) {
            const pack = promoPacks.find(p => p.id === packId);
            if (!pack || !pack.type) {
                // Fallback for old packs without type
                openModal(packId, 'pack');
                return;
            }

            currentBundle = {
                id: pack.id,
                type: pack.type,
                slot1: null,
                slot2: null,
                price: pack.newPrice,
                name: pack.name,
                // Store the rules directly from pack
                slot1Group: pack.slot1Group || 'A',
                slot2Group: pack.slot2Group || 'A'
            };

            // Reset UI
            document.getElementById('bundle-title').textContent = pack.name;
            document.getElementById('bundle-subtitle').textContent = pack.description;
            document.getElementById('bundle-price-display').textContent = pack.newPrice + ' DH';
            
            resetSlotUI(1);
            resetSlotUI(2);
            
            // Reset variant picker state
            document.getElementById('bundle-variant-area').classList.add('hidden');
            document.getElementById('bundle-selection-area').classList.remove('hidden');
            pendingBundleProduct = null;
            
            // Lock body scroll
            document.body.style.overflow = 'hidden';

            const modal = document.getElementById('bundle-modal');
            modal.classList.remove('hidden');
            // Add active class for animation
            setTimeout(() => { modal.classList.add('active'); }, 10);
            
            // Handle Default Slots - for products with variants, select first variant automatically
            if (pack.defaultSlot1) {
                const defaultProduct1 = availableForBundle.find(p => p.id === pack.defaultSlot1);
                if (defaultProduct1) {
                    const variant1 = defaultProduct1.variants && defaultProduct1.variants.length > 0 ? defaultProduct1.variants[0] : null;
                    finalizeBundleSelection(defaultProduct1, variant1, 1);
                }
            }
            if (pack.defaultSlot2) {
                const defaultProduct2 = availableForBundle.find(p => p.id === pack.defaultSlot2);
                if (defaultProduct2) {
                    const variant2 = defaultProduct2.variants && defaultProduct2.variants.length > 0 ? defaultProduct2.variants[0] : null;
                    finalizeBundleSelection(defaultProduct2, variant2, 2);
                }
            }

            // If no defaults, activate slot 1
            if (!pack.defaultSlot1) {
                activateSlot(1);
            } else if (!pack.defaultSlot2) {
                activateSlot(2);
            } else {
                // Both defaults set, just render products for slot 1
                activateSlot(1);
                renderBundleProducts(currentBundle.slot1Group);
            }
            
            updateBundleButton();
        }

        function closeBundleModal() {
            const modal = document.getElementById('bundle-modal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        function getBundleRules(type) {
            // Find the pack to get its specific rules
            const pack = promoPacks.find(p => p.type === type);
            if (pack && pack.slot1Group && pack.slot2Group) {
                return { slot1: pack.slot1Group, slot2: pack.slot2Group };
            }
            
            // Fallback for legacy or hardcoded types if not set in DB
            if (type === 'pack1') return { slot1: 'A', slot2: 'A' }; // Duo Copines
            if (type === 'pack2') return { slot1: 'C', slot2: 'A' }; // Buy 1 Get 1
            if (type === 'pack3') return { slot1: 'B', slot2: 'B' }; // Collection
            return { slot1: 'A', slot2: 'A' };
        }

        function activateSlot(slotNum) {
            activeSlot = slotNum;
            
            // Highlight active slot - update for compact header style
            document.querySelectorAll('.bundle-slot').forEach(el => {
                el.classList.remove('active', 'border-brand-gold');
                el.classList.add('border-gray-600');
            });
            const activeEl = document.getElementById(`slot-${slotNum}`);
            activeEl.classList.add('active', 'border-brand-gold');
            activeEl.classList.remove('border-gray-600');
            
            document.getElementById('active-slot-label').textContent = `Montre ${slotNum}`;

            // Filter Products using stored groups from currentBundle
            const requiredGroup = (slotNum === 1) ? currentBundle.slot1Group : currentBundle.slot2Group;
            
            // Go back to product selection view
            document.getElementById('bundle-variant-area').classList.add('hidden');
            document.getElementById('bundle-selection-area').classList.remove('hidden');
            
            // Scroll to top
            document.getElementById('bundle-scroll-area').scrollTop = 0;
            
            renderBundleProducts(requiredGroup);
        }

        function renderBundleProducts(group) {
            const container = document.getElementById('bundle-products-grid');
            
            // Filter products from BOTH collections based on Product Group
            // Also include products without a group if group is 'ANY' or allow selection
            const eligibleProducts = availableForBundle.filter(p => p.group === group);
            
            if (eligibleProducts.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-400 mb-2">Aucun produit dans le Groupe ${group}.</p>
                        <p class="text-xs text-gray-500">Assurez-vous d'attribuer des produits au groupe ${group} dans l'admin.</p>
                    </div>
                `;
                return;
            }

            // Render Products (Not Variants)
            container.innerHTML = eligibleProducts.map(p => `
                <div onclick="selectBundleProduct(${p.id}, null)" class="cursor-pointer border rounded-lg p-2 hover:shadow-md transition flex flex-col items-center text-center bg-white h-full">
                    <img src="${escapeHtml(p.image)}" class="w-20 h-20 object-cover mb-2 rounded">
                    <h4 class="text-xs font-bold line-clamp-1">${escapeHtml(p.name)}</h4>
                    <p class="text-xs text-gray-500">${escapeHtml(p.category)}</p>
                </div>
            `).join('');
        }

        function selectBundleProduct(productId, variantIndex, forceSlot = null) {
            const product = availableForBundle.find(p => p.id === productId);
            if (!product) return;

            // Determine which slot to use
            const targetSlot = forceSlot || activeSlot;
            
            // Check if product has variants - if so, show variant picker
            if (product.variants && product.variants.length > 0) {
                showBundleVariantPicker(product, targetSlot);
                return;
            }

            // No variants - select directly
            finalizeBundleSelection(product, null, targetSlot);
        }
        
        function showBundleVariantPicker(product, targetSlot) {
            pendingBundleProduct = product;
            pendingBundleSlot = targetSlot;
            pendingBundleVariantIndex = 0;
            
            // Hide products grid, show variant picker
            document.getElementById('bundle-selection-area').classList.add('hidden');
            document.getElementById('bundle-variant-area').classList.remove('hidden');
            
            // Scroll to top
            document.getElementById('bundle-scroll-area').scrollTop = 0;
            
            // Set product info
            document.getElementById('bundle-variant-product-name').textContent = product.name;
            
            // Build variant options - larger cards with color swatch for better mobile UX
            const optionsHtml = product.variants.map((v, index) => `
                <div onclick="selectBundleVariant(${index})" 
                     class="variant-card cursor-pointer rounded-xl overflow-hidden border-2 ${index === 0 ? 'selected border-brand-gold' : 'border-gray-200'} bg-white shadow-sm hover:shadow-md transition-all flex items-center gap-3 p-2 min-w-[140px]"
                     data-variant-index="${index}">
                    <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                        <img src="${escapeHtml(v.image || product.image)}" class="w-full h-full object-cover pointer-events-none">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full border border-gray-300 flex-shrink-0" style="background-color: ${v.color || '#ccc'}"></span>
                            <span class="text-sm font-medium truncate">${escapeHtml(v.name)}</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-brand-gold ${index === 0 ? '' : 'hidden'}"></i>
                    </div>
                </div>
            `).join('');
            
            document.getElementById('bundle-variant-options').innerHTML = optionsHtml;
            
            // Select first variant by default
            selectBundleVariant(0);
        }
        
        function selectBundleVariant(variantIndex) {
            if (!pendingBundleProduct) return;
            
            pendingBundleVariantIndex = variantIndex;
            const variant = pendingBundleProduct.variants[variantIndex];
            
            // Build ALL product images (for toggle)
            bundleAllProductImages = [pendingBundleProduct.image];
            if (pendingBundleProduct.gallery && pendingBundleProduct.gallery.length > 0) {
                pendingBundleProduct.gallery.forEach(img => {
                    if (!bundleAllProductImages.includes(img)) {
                        bundleAllProductImages.push(img);
                    }
                });
            }
            
            // Build variant-specific gallery
            bundleVariantImages = [];
            
            // Add variant's main image first
            if (variant.image) {
                bundleVariantImages.push(variant.image);
            }
            
            // Add variant-specific gallery if it exists
            if (variant.gallery && variant.gallery.length > 0) {
                variant.gallery.forEach(img => {
                    if (!bundleVariantImages.includes(img)) {
                        bundleVariantImages.push(img);
                    }
                });
            }
            
            // Fallback to product image if variant has no images
            if (bundleVariantImages.length === 0) {
                bundleVariantImages.push(pendingBundleProduct.image);
            }
            
            // Start with variant images
            bundleGalleryMode = 'variant';
            bundleGalleryImages = [...bundleVariantImages];
            bundleGalleryIndex = 0;
            
            // Update preview image
            document.getElementById('bundle-variant-image').src = bundleGalleryImages[0];
            
            // Show/hide gallery toggle button
            const toggleBtn = document.getElementById('bundle-gallery-toggle');
            if (bundleAllProductImages.length > bundleVariantImages.length) {
                toggleBtn.classList.remove('hidden');
                document.getElementById('bundle-gallery-toggle-text').textContent = 'Toutes';
            } else {
                toggleBtn.classList.add('hidden');
            }
            
            // Update navigation UI
            updateBundleGalleryNav();
            updateBundleGalleryThumbs();
            
            // Build gallery thumbnails if more than 1 image - horizontally scrollable
            const galleryThumbsContainer = document.getElementById('bundle-gallery-thumbs');
            const galleryGrid = document.getElementById('bundle-gallery-grid');
            
            if (bundleGalleryImages.length > 1) {
                galleryThumbsContainer.classList.remove('hidden');
                galleryGrid.innerHTML = bundleGalleryImages.map((img, idx) => `
                    <div onclick="jumpToBundleGallery(${idx})" 
                         class="bundle-gallery-thumb cursor-pointer rounded-lg overflow-hidden w-14 h-14 flex-shrink-0 border-2 ${idx === 0 ? 'border-brand-gold' : 'border-gray-200'} transition-all">
                        <img src="${escapeHtml(img)}" class="w-full h-full object-cover pointer-events-none">
                    </div>
                `).join('');
            } else {
                galleryThumbsContainer.classList.add('hidden');
            }
            
            // Update selection state on variant cards
            document.querySelectorAll('.variant-card').forEach((card) => {
                const idx = parseInt(card.dataset.variantIndex);
                const checkIcon = card.querySelector('.fa-check-circle');
                if (idx === variantIndex) {
                    card.classList.remove('border-gray-200');
                    card.classList.add('selected', 'border-brand-gold');
                    if (checkIcon) checkIcon.classList.remove('hidden');
                } else {
                    card.classList.remove('selected', 'border-brand-gold');
                    card.classList.add('border-gray-200');
                    if (checkIcon) checkIcon.classList.add('hidden');
                }
            });
        }
        
        function toggleBundleGalleryMode() {
            if (bundleGalleryMode === 'variant') {
                // Switch to all product images
                bundleGalleryMode = 'all';
                bundleGalleryImages = [...bundleAllProductImages];
                document.getElementById('bundle-gallery-toggle-text').textContent = pendingBundleProduct.variants[pendingBundleVariantIndex].name;
            } else {
                // Switch back to variant images
                bundleGalleryMode = 'variant';
                bundleGalleryImages = [...bundleVariantImages];
                document.getElementById('bundle-gallery-toggle-text').textContent = 'Toutes';
            }
            
            bundleGalleryIndex = 0;
            document.getElementById('bundle-variant-image').src = bundleGalleryImages[0];
            updateBundleGalleryNav();
            
            // Rebuild thumbnails
            const galleryThumbsContainer = document.getElementById('bundle-gallery-thumbs');
            const galleryGrid = document.getElementById('bundle-gallery-grid');
            
            if (bundleGalleryImages.length > 1) {
                galleryThumbsContainer.classList.remove('hidden');
                galleryGrid.innerHTML = bundleGalleryImages.map((img, idx) => `
                    <div onclick="jumpToBundleGallery(${idx})" 
                         class="bundle-gallery-thumb cursor-pointer rounded-lg overflow-hidden w-14 h-14 flex-shrink-0 border-2 ${idx === 0 ? 'border-brand-gold' : 'border-gray-200'} transition-all">
                        <img src="${escapeHtml(img)}" class="w-full h-full object-cover pointer-events-none">
                    </div>
                `).join('');
            } else {
                galleryThumbsContainer.classList.add('hidden');
            }
        }
        
        function navigateBundleGallery(direction) {
            if (bundleGalleryImages.length <= 1) return;
            
            bundleGalleryIndex += direction;
            if (bundleGalleryIndex < 0) bundleGalleryIndex = bundleGalleryImages.length - 1;
            if (bundleGalleryIndex >= bundleGalleryImages.length) bundleGalleryIndex = 0;
            
            document.getElementById('bundle-variant-image').src = bundleGalleryImages[bundleGalleryIndex];
            updateBundleGalleryNav();
            updateBundleGalleryThumbs();
        }
        
        function jumpToBundleGallery(index) {
            bundleGalleryIndex = index;
            document.getElementById('bundle-variant-image').src = bundleGalleryImages[bundleGalleryIndex];
            updateBundleGalleryNav();
            updateBundleGalleryThumbs();
        }
        
        function updateBundleGalleryNav() {
            const prevBtn = document.getElementById('bundle-prev-btn');
            const nextBtn = document.getElementById('bundle-next-btn');
            const counter = document.getElementById('bundle-image-counter');
            
            if (bundleGalleryImages.length > 1) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
                counter.classList.remove('hidden');
                document.getElementById('bundle-current-idx').textContent = bundleGalleryIndex + 1;
                document.getElementById('bundle-total-imgs').textContent = bundleGalleryImages.length;
            } else {
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
                counter.classList.add('hidden');
            }
        }
        
        function updateBundleGalleryThumbs() {
            document.querySelectorAll('.bundle-gallery-thumb').forEach((thumb, idx) => {
                if (idx === bundleGalleryIndex) {
                    thumb.classList.remove('border-gray-200');
                    thumb.classList.add('border-brand-gold');
                } else {
                    thumb.classList.remove('border-brand-gold');
                    thumb.classList.add('border-gray-200');
                }
            });
        }
        
        function confirmBundleVariant() {
            if (!pendingBundleProduct) return;
            
            const variant = pendingBundleProduct.variants[pendingBundleVariantIndex];
            finalizeBundleSelection(pendingBundleProduct, variant, pendingBundleSlot);
            
            // Reset and go back to products view
            backToProducts();
        }
        
        function backToProducts() {
            document.getElementById('bundle-variant-area').classList.add('hidden');
            document.getElementById('bundle-selection-area').classList.remove('hidden');
            pendingBundleProduct = null;
            bundleGalleryImages = [];
            bundleGalleryIndex = 0;
            
            // Scroll to top
            document.getElementById('bundle-scroll-area').scrollTop = 0;
        }
        
        function finalizeBundleSelection(product, variant, targetSlot) {
            let selectedItem = {
                id: product.id,
                name: variant ? `${product.name} (${variant.name})` : product.name,
                image: variant ? (variant.image || product.image) : product.image,
                variantName: variant ? variant.name : "Standard"
            };

            if (targetSlot === 1) currentBundle.slot1 = selectedItem;
            else currentBundle.slot2 = selectedItem;

            // Update Slot UI - new compact header style
            const slotEl = document.getElementById(`slot-${targetSlot}`);
            slotEl.querySelector('.placeholder').classList.add('hidden');
            const imgEl = slotEl.querySelector('.slot-img');
            imgEl.src = selectedItem.image;
            imgEl.classList.remove('hidden');
            slotEl.querySelector('.clear-btn').classList.remove('hidden');
            slotEl.querySelector('.clear-btn').classList.add('flex');
            
            // Update slot label with variant name
            const labelEl = slotEl.querySelector('.slot-label');
            if (labelEl) {
                labelEl.textContent = selectedItem.variantName;
            }

            // Auto-advance to next slot if not already filled
            if (targetSlot === 1 && !currentBundle.slot2) {
                setTimeout(() => activateSlot(2), 300);
            }

            updateBundleButton();
        }
        
        function updateBundleButton() {
            const btn = document.getElementById('add-bundle-btn');
            if (currentBundle && currentBundle.slot1 && currentBundle.slot2) {
                btn.disabled = false;
                btn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                btn.classList.add('bg-brand-gold', 'hover:bg-brand-black');
                btn.innerHTML = `<i class="fas fa-shopping-bag"></i><span>Ajouter au Panier - ${currentBundle.price} DH</span>`;
            } else {
                btn.disabled = true;
                btn.classList.add('bg-gray-300', 'cursor-not-allowed');
                btn.classList.remove('bg-brand-gold', 'hover:bg-brand-black');
                const count = (currentBundle?.slot1 ? 1 : 0) + (currentBundle?.slot2 ? 1 : 0);
                btn.innerHTML = `<i class="fas fa-shopping-bag"></i><span>Sélectionnez ${2 - count} montre${2 - count > 1 ? 's' : ''}</span>`;
            }
        }

        function clearSlot(e, slotNum) {
            e.stopPropagation();
            if (slotNum === 1) currentBundle.slot1 = null;
            else currentBundle.slot2 = null;
            
            resetSlotUI(slotNum);
            updateBundleButton();
            activateSlot(slotNum);
        }

        function resetSlotUI(slotNum) {
            const slotEl = document.getElementById(`slot-${slotNum}`);
            slotEl.querySelector('.placeholder').classList.remove('hidden');
            slotEl.querySelector('.slot-img').classList.add('hidden');
            slotEl.querySelector('.clear-btn').classList.add('hidden');
            slotEl.querySelector('.clear-btn').classList.remove('flex');
            
            // Clear slot label
            const labelEl = slotEl.querySelector('.slot-label');
            if (labelEl) labelEl.textContent = '';
        }

        function addBundleToCart() {
            if (!currentBundle.slot1 || !currentBundle.slot2) return;

            const cartItemId = `bundle-${currentBundle.id}-${Date.now()}`;
            
            cart.push({
                cartId: cartItemId,
                id: currentBundle.id,
                name: currentBundle.name,
                variant: `${currentBundle.slot1.variantName} + ${currentBundle.slot2.variantName}`,
                price: currentBundle.price,
                image: currentBundle.slot1.image, // Use first image as thumbnail
                quantity: 1,
                isBundle: true,
                items: [currentBundle.slot1, currentBundle.slot2]
            });

            saveCart();
            updateCartUI();
            closeBundleModal();
            showToast("Pack ajouté au panier !");
            toggleCart();
        }

        // --- RENDER FUNCTIONS ---
        
        // 1. New Arrivals
        function renderNewArrivals() {
            const grid = document.getElementById('new-arrivals-grid');
            grid.innerHTML = newArrivals.map(product => `
                <div class="product-card cursor-pointer group relative bg-white pb-4 rounded-xl overflow-hidden hover:shadow-2xl transition duration-300 border border-gray-100" onclick="openModal(${product.id}, 'new')">
                    <div class="absolute top-4 left-4 z-10 bg-brand-black text-brand-gold text-[10px] font-bold px-3 py-1 rounded-full badge-pulse uppercase tracking-widest border border-brand-gold">
                        Nouveau
                    </div>
                    <div class="relative overflow-hidden aspect-w-4 aspect-h-5 h-64 sm:h-80 bg-gray-100 mb-4">
                        <img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" loading="lazy" class="product-img w-full h-full object-cover object-center transition duration-500 ease-in-out transform group-hover:scale-110">
                    </div>
                    <div class="text-center px-4">
                        <h3 class="font-serif text-lg text-brand-black font-bold mb-1">${escapeHtml(product.name)}</h3>
                        <p class="font-arabic text-sm text-gray-400 mb-2">${escapeHtml(product.arabicName)}</p>
                        <p class="text-brand-gold font-bold text-xl">${product.price} DH</p>
                    </div>
                </div>
            `).join('');
        }

        // 2. Main Products
        function renderProducts() {
            const productsToShow = allProducts.slice(0, visibleProducts);
            const grid = document.getElementById('product-grid');
            grid.innerHTML = productsToShow.map(product => `
                <div class="product-card cursor-pointer group relative bg-white pb-2 md:pb-4 rounded-lg overflow-hidden hover:shadow-lg transition duration-300 border border-gray-100" onclick="openModal(${product.id}, 'collection')">
                    <div class="relative overflow-hidden aspect-w-1 aspect-h-1 h-40 md:h-80 bg-gray-100 mb-2 md:mb-4">
                        <img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" loading="lazy" class="product-img w-full h-full object-cover object-center transition duration-500 ease-in-out">
                        <div class="absolute inset-x-0 bottom-0 p-2 md:p-4 opacity-0 group-hover:opacity-100 transition duration-300 translate-y-4 group-hover:translate-y-0">
                            <span class="block w-full text-center bg-brand-black text-white py-2 md:py-3 uppercase text-[10px] md:text-xs tracking-widest shadow-lg rounded font-bold">
                                Voir Détails
                            </span>
                        </div>
                    </div>
                    <div class="text-center px-2 md:px-4">
                        <p class="text-gray-400 text-[10px] md:text-xs uppercase tracking-wider mb-1 truncate">${escapeHtml(product.category)}</p>
                        <h3 class="font-serif text-sm md:text-lg text-brand-black mb-0 truncate">${escapeHtml(product.name)}</h3>
                        <p class="font-arabic text-xs md:text-sm text-gray-500 mb-1 md:mb-2 truncate">${escapeHtml(product.arabicName)}</p>
                        
                        <!-- Price Block -->
                        <div class="flex items-center justify-center gap-2 mb-2">
                             ${product.oldPrice ? `<span class="text-gray-400 line-through text-xs md:text-sm">${product.oldPrice} DH</span>` : ''}
                             <p class="text-brand-gold font-bold text-base md:text-xl">${product.price} DH</p>
                        </div>

                        ${product.variants && product.variants.length > 1 ? 
                            `<div class="flex justify-center gap-1 mt-1 md:mt-2">
                                ${product.variants.map(v => `<div class="w-2 h-2 md:w-3 md:h-3 rounded-full border border-gray-200" style="background-color: ${escapeHtml(v.color)}"></div>`).join('')}
                             </div>` : ''
                        }
                    </div>
                </div>
            `).join('');

            if (visibleProducts >= allProducts.length) {
                document.getElementById('load-more-btn').style.display = 'none';
            }
        }

        // 3. Packs
        function renderPacks() {
            const container = document.getElementById('packs-container');
            container.innerHTML = promoPacks.map(pack => `
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-brand-rose/20 flex flex-col md:flex-row h-full md:h-80 relative group">
                    <div class="absolute top-4 left-4 z-10 bg-brand-red text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">PROMO -25%</div>
                    <div class="md:w-1/2 relative overflow-hidden cursor-pointer" onclick="${pack.type ? `openBundleModal(${pack.id})` : `openModal(${pack.id}, 'pack')`}">
                        <img src="${escapeHtml(pack.image)}" loading="lazy" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="${escapeHtml(pack.name)}">
                    </div>
                    <div class="md:w-1/2 p-8 flex flex-col justify-center text-left">
                        <h3 class="font-serif text-2xl font-bold text-brand-black mb-1 cursor-pointer" onclick="${pack.type ? `openBundleModal(${pack.id})` : `openModal(${pack.id}, 'pack')`}">${escapeHtml(pack.name)}</h3>
                        <h4 class="font-arabic text-lg text-gray-500 mb-3">${escapeHtml(pack.arabicName)}</h4>
                        <p class="text-sm text-gray-600 mb-4">${escapeHtml(pack.description)}</p>
                        <div class="flex items-center gap-4 mb-6">
                            <span class="text-gray-400 line-through text-lg">${pack.oldPrice} DH</span>
                            <span class="text-brand-red font-bold text-3xl">${pack.newPrice} DH</span>
                        </div>
                        <button onclick="${pack.type ? `openBundleModal(${pack.id})` : `addToCart(${pack.id}, 'pack')`}" class="shimmer bg-brand-black text-white py-3 px-6 rounded-lg uppercase tracking-widest text-sm font-bold hover:bg-brand-gold transition shadow-lg w-full md:w-auto">
                            ${pack.type ? 'Composer le Pack' : 'Commander'}
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // --- MODAL & CART LOGIC ---

        function findProduct(id, type) {
            // type should be: 'pack', 'new', or 'collection'
            if (type === 'pack') return promoPacks.find(p => p.id === id);
            if (type === 'new') return newArrivals.find(p => p.id === id);
            // 'collection' or any other value defaults to allProducts
            return allProducts.find(p => p.id === id);
        }

        function openModal(id, type = 'collection') {
            const product = findProduct(id, type);
            if (!product) return;

            // Store product reference for variant selection
            currentModalProduct = product;
            currentModalType = type;

            // Basic Info
            document.getElementById('modal-title').textContent = product.name;
            document.getElementById('modal-arabic').textContent = product.arabicName;
            
            // Logic for Price
            let currentPrice = product.price;
            let showOldPrice = false;
            let oldPriceVal = 0;

            if (type === 'pack') {
                currentPrice = product.newPrice;
                if (product.oldPrice) {
                    showOldPrice = true;
                    oldPriceVal = product.oldPrice;
                }
            } else {
                 if (product.oldPrice) {
                    showOldPrice = true;
                    oldPriceVal = product.oldPrice;
                }
            }
            
            document.getElementById('modal-price').textContent = currentPrice + ' DH';
            document.getElementById('modal-category').textContent = product.category;

            const oldPriceEl = document.getElementById('modal-old-price');
            if (showOldPrice) {
                oldPriceEl.textContent = oldPriceVal + ' DH';
                oldPriceEl.classList.remove('hidden');
            } else {
                oldPriceEl.classList.add('hidden');
            }

            // Variant and Gallery Logic
            const variantSection = document.getElementById('variant-section');
            const variantOptionsDiv = document.getElementById('variant-options');
            currentSelectedVariant = null; 
            
            // Reset Quantity
            currentModalQty = 1;
            document.getElementById('modal-qty').textContent = currentModalQty;

            // Check if product has real variants (non-empty array)
            const hasVariants = product.variants && product.variants.length > 0;
            // Check if product has gallery images
            const hasGallery = product.gallery && product.gallery.length > 0;

            // Build image array for navigation - ONLY gallery images (not variants)
            // Start with main product image, then add gallery
            modalImages = [product.image];
            if (hasGallery) {
                product.gallery.forEach(img => {
                    if (!modalImages.includes(img)) {
                        modalImages.push(img);
                    }
                });
            }
            
            // Store all product images for gallery toggle
            allProductImages = [...modalImages];
            variantImages = [...modalImages]; // Default to all
            modalGalleryMode = 'all';
            
            currentImageIndex = 0;
            
            // Always set main image first
            document.getElementById('modal-image').src = product.image;
            
            // Hide gallery toggle by default
            document.getElementById('modal-gallery-toggle').classList.add('hidden');
            
            updateImageNavigation();

            if (hasVariants) {
                // Product has variants - show variants
                variantSection.classList.remove('hidden');
                currentSelectedVariant = product.variants[0];
                // Don't change the main image here - keep product.image as default
                // Variant image will be shown when user clicks a variant
                document.getElementById('selected-variant-name').textContent = currentSelectedVariant.name;

                // Build variant thumbnails (color swatches)
                let thumbnailsHtml = product.variants.map((v, index) => `
                    <div onclick="selectVariant(${id}, ${index}, '${type}')" 
                         class="variant-option cursor-pointer rounded-lg overflow-hidden w-14 h-14 md:w-16 md:h-16 relative shadow-sm hover:shadow-md border-2 ${index === 0 ? 'selected border-brand-gold' : 'border-transparent'}"
                         title="${escapeHtml(v.name)}">
                        <img src="${escapeHtml(v.image || product.image)}" class="w-full h-full object-cover pointer-events-none">
                        <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[8px] py-0.5 text-center truncate px-0.5">${escapeHtml(v.name)}</div>
                    </div>
                `).join('');
                
                variantOptionsDiv.innerHTML = thumbnailsHtml;
                
            } else if (hasGallery) {
                // No variants but has gallery - hide variant section, arrows will handle gallery
                variantSection.classList.add('hidden');
            } else {
                // No variants and no gallery
                variantSection.classList.add('hidden');
                document.getElementById('modal-image').src = product.image;
            }

            const addBtn = document.getElementById('modal-add-btn');
            addBtn.onclick = function() {
                addToCart(product.id, type, currentModalQty);
                closeModal();
            };

            const buyBtn = document.getElementById('modal-buy-now-btn');
            buyBtn.onclick = function() {
                addToCart(product.id, type, currentModalQty);
                closeModal();
                openCheckoutModal();
            };

            const modal = document.getElementById('product-modal');
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.add('active'); }, 10);
        }

        // Track gallery mode: 'all' = all product images, 'variant' = variant-specific
        let modalGalleryMode = 'all';
        let allProductImages = []; // Store all product images for toggle
        let variantImages = []; // Store current variant images

        function selectVariant(productId, variantIndex, type) {
            // Use stored product reference for reliability
            const product = currentModalProduct;
            if (!product || !product.variants || !product.variants[variantIndex]) {
                console.error('Variant not found:', productId, variantIndex, type);
                return;
            }
            
            const variant = product.variants[variantIndex];
            currentSelectedVariant = variant;
            
            // Build variant-specific gallery
            variantImages = [];
            
            // Add variant's main image first
            if (variant.image) {
                variantImages.push(variant.image);
            }
            
            // Add variant's gallery if exists
            if (variant.gallery && variant.gallery.length > 0) {
                variant.gallery.forEach(img => {
                    if (!variantImages.includes(img)) {
                        variantImages.push(img);
                    }
                });
            }
            
            // If variant has no images, use product image
            if (variantImages.length === 0) {
                variantImages.push(product.image);
            }
            
            // Switch to variant gallery mode
            modalGalleryMode = 'variant';
            modalImages = [...variantImages];
            currentImageIndex = 0;
            
            // Update display
            document.getElementById('modal-image').src = modalImages[0];
            document.getElementById('selected-variant-name').textContent = variant.name;
            
            // Show/update gallery toggle button
            const toggleBtn = document.getElementById('modal-gallery-toggle');
            if (allProductImages.length > variantImages.length) {
                toggleBtn.classList.remove('hidden');
                document.getElementById('gallery-toggle-text').textContent = 'Toutes';
            } else {
                toggleBtn.classList.add('hidden');
            }
            
            updateImageNavigation();
            
            // Update variant selection styling
            const options = document.querySelectorAll('.variant-option');
            options.forEach((opt, idx) => {
                opt.classList.remove('selected', 'border-brand-gold');
                opt.classList.add('border-transparent');
            });
            if (options[variantIndex]) {
                options[variantIndex].classList.add('selected', 'border-brand-gold');
                options[variantIndex].classList.remove('border-transparent');
            }
        }
        
        function toggleModalGalleryMode() {
            if (modalGalleryMode === 'variant') {
                // Switch to all images
                modalGalleryMode = 'all';
                modalImages = [...allProductImages];
                document.getElementById('gallery-toggle-text').textContent = currentSelectedVariant ? currentSelectedVariant.name : 'Variante';
            } else {
                // Switch back to variant images
                modalGalleryMode = 'variant';
                modalImages = [...variantImages];
                document.getElementById('gallery-toggle-text').textContent = 'Toutes';
            }
            
            currentImageIndex = 0;
            document.getElementById('modal-image').src = modalImages[0];
            updateImageNavigation();
        }

        function selectGalleryImage(element, imageSrc) {
            // Update main image
            document.getElementById('modal-image').src = imageSrc;
            
            // Update current index for navigation
            const imgIndex = modalImages.indexOf(imageSrc);
            if (imgIndex !== -1) {
                currentImageIndex = imgIndex;
                updateImageCounter();
            }
        }

        // Image Navigation Functions
        function navigateModalImage(direction) {
            if (modalImages.length <= 1) return;
            
            currentImageIndex += direction;
            
            // Loop around
            if (currentImageIndex < 0) currentImageIndex = modalImages.length - 1;
            if (currentImageIndex >= modalImages.length) currentImageIndex = 0;
            
            document.getElementById('modal-image').src = modalImages[currentImageIndex];
            updateImageCounter();
        }
        
        function updateImageNavigation() {
            const prevBtn = document.getElementById('modal-prev-btn');
            const nextBtn = document.getElementById('modal-next-btn');
            const counter = document.getElementById('modal-image-counter');
            
            if (modalImages.length > 1) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
                counter.classList.remove('hidden');
                updateImageCounter();
            } else {
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
                counter.classList.add('hidden');
            }
        }
        
        function updateImageCounter() {
            document.getElementById('modal-current-index').textContent = currentImageIndex + 1;
            document.getElementById('modal-total-images').textContent = modalImages.length;
        }

        function closeModal() {
            const modal = document.getElementById('product-modal');
            modal.classList.remove('active');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        // Add to Cart Logic
        function addToCart(id, type = 'collection', qty = 1) {
            const product = findProduct(id, type);
            
            const variantName = currentSelectedVariant ? currentSelectedVariant.name : "Standard";
            const variantImage = currentSelectedVariant ? currentSelectedVariant.image : product.image;
            
            const finalPrice = (type === 'pack') ? product.newPrice : product.price;

            const cartItemId = `${product.id}-${variantName.replace(/\s+/g, '')}`;
            const existingItem = cart.find(item => item.cartId === cartItemId);

            if (existingItem) {
                existingItem.quantity += qty;
            } else {
                cart.push({
                    cartId: cartItemId,
                    id: product.id,
                    name: product.name,
                    variant: variantName,
                    price: finalPrice,
                    image: variantImage,
                    quantity: qty
                });
            }

            saveCart();
            updateCartUI();
            showToast(`Ajouté: ${product.name} (x${qty})`);
            
            const cartSidebar = document.getElementById('cart-sidebar');
            if (cartSidebar.classList.contains('translate-x-full')) {
                toggleCart();
            }
        }

        function removeFromCart(cartId) {
            cart = cart.filter(item => item.cartId !== cartId);
            saveCart();
            updateCartUI();
        }

        function updateQuantity(cartId, change) {
            const item = cart.find(item => item.cartId === cartId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(cartId);
                } else {
                    saveCart();
                    updateCartUI();
                }
            }
        }

        function saveCart() {
            localStorage.setItem('swisCart', JSON.stringify(cart));
        }

        function updateCartUI() {
            const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const countEl = document.getElementById('cart-count');
            countEl.textContent = totalCount;
            totalCount > 0 ? countEl.classList.remove('scale-0') : countEl.classList.add('scale-0');

            const container = document.getElementById('cart-items');
            if (cart.length === 0) {
                container.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-gray-400"><i class="fas fa-shopping-basket text-4xl mb-4 text-gray-300"></i><p>Votre panier est vide</p></div>`;
            } else {
                container.innerHTML = cart.map(item => `
                    <div class="flex items-center space-x-4 border-b pb-4 border-gray-100 last:border-0">
                        <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}" class="w-16 h-16 object-cover rounded shadow-sm">
                        <div class="flex-1">
                            <h4 class="font-serif text-sm font-bold text-brand-black">${escapeHtml(item.name)}</h4>
                            <p class="text-xs text-gray-500 mb-1">Color: ${escapeHtml(item.variant)}</p>
                            <p class="text-xs text-brand-gold font-bold">${item.price} DH</p>
                            <div class="flex items-center mt-2 space-x-2">
                                <button onclick="updateQuantity('${escapeHtml(item.cartId)}', -1)" class="w-6 h-6 rounded-full bg-gray-100 text-xs">-</button>
                                <span class="text-xs font-semibold w-4 text-center">${item.quantity}</span>
                                <button onclick="updateQuantity('${escapeHtml(item.cartId)}', 1)" class="w-6 h-6 rounded-full bg-gray-100 text-xs">+</button>
                            </div>
                        </div>
                        <div class="text-right flex flex-col justify-between h-16">
                            <p class="font-bold text-sm">${item.price * item.quantity} DH</p>
                            <button onclick="removeFromCart('${escapeHtml(item.cartId)}')" class="text-xs text-red-400 hover:text-red-600 transition underline"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                `).join('');
            }
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('cart-total').textContent = total + " DH";
        }

        function toggleCart() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            sidebar.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navbar');
            const mobileMenu = document.getElementById('mobile-menu');
            // Check if click is outside the navbar and the menu is NOT hidden
            if (!nav.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Keyboard navigation for modal images
        document.addEventListener('keydown', function(event) {
            const productModal = document.getElementById('product-modal');
            if (productModal && productModal.classList.contains('active')) {
                if (event.key === 'ArrowLeft') {
                    navigateModalImage(-1);
                } else if (event.key === 'ArrowRight') {
                    navigateModalImage(1);
                } else if (event.key === 'Escape') {
                    closeModal();
                }
            }
        });

        function showToast(msg) {
            const toast = document.getElementById('toast');
            toast.querySelector('span').textContent = msg;
            toast.style.opacity = '1';
            setTimeout(() => { toast.style.opacity = '0'; }, 3000);
        }

        // --- CHECKOUT LOGIC (NEW) ---
        
        function openCheckoutModal() {
            if (cart.length === 0) return showToast("Votre panier est vide!");
            const modal = document.getElementById('checkout-modal');
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.add('active'); }, 10);
            
            // Close Cart sidebar to focus on checkout
            toggleCart();
        }

        function closeCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            modal.classList.remove('active');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        function finalizeOrder() {
            const name = document.getElementById('checkout-name').value;
            const phone = document.getElementById('checkout-phone').value;
            const city = document.getElementById('checkout-city').value;
            const address = document.getElementById('checkout-address').value;

            if (!name || !phone || !city || !address) {
                alert("Merci de remplir toutes les informations / المرجو ملء جميع المعلومات");
                return;
            }

            const phoneNum = "<?php echo $settings['whatsapp_number']; ?>";
            let msg = `👋 *NOUVELLE COMMANDE / طلب جديد* \n`;
            msg += `--------------------------------\n`;
            msg += `👤 *Client:* ${name}\n`;
            msg += `📱 *Tel:* ${phone}\n`;
            msg += `🏙️ *Ville:* ${city}\n`;
            msg += `📍 *Adresse:* ${address}\n`;
            msg += `--------------------------------\n\n`;
            msg += `*ARTICLES:*\n`;

            let total = 0;
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                if (item.isBundle) {
                    msg += `📦 *${item.name}* x${item.quantity} - ${itemTotal} <?php echo $settings['currency']; ?>\n`;
                    msg += `   ├ ${item.items[0].name}\n`;
                    msg += `   └ ${item.items[1].name}\n`;
                } else {
                    msg += `▪️ ${item.name} (${item.variant}) x${item.quantity} - ${itemTotal} <?php echo $settings['currency']; ?>\n`;
                }
                total += itemTotal;
            });
            msg += `\n💰 *TOTAL: ${total} <?php echo $settings['currency']; ?>*`;
            
            // Close modal
            closeCheckoutModal();
            
            // Send to WhatsApp
            const whatsappUrl = `https://api.whatsapp.com/send?phone=${phoneNum}&text=${encodeURIComponent(msg)}`;
            window.open(whatsappUrl, '_blank');
        }

        // Initialize
        document.getElementById('load-more-btn').addEventListener('click', () => {
            visibleProducts += 4;
            renderProducts();
        });
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            window.scrollY > 50 ? nav.classList.add('shadow-md') : nav.classList.remove('shadow-md');
        });

        // Run
        startCountdown();
        renderProducts();
        renderNewArrivals(); 
        renderPacks();
        updateCartUI();
    </script>
</body>
</html>