<?php
/**
 * Swis Brands - Homepage
 * Main landing page with products, cart, and checkout
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Initialize application
require_once __DIR__ . '/includes/bootstrap.php';

// Get data
$settings = getSettings();
$products = getProductsData();

// Page metadata
$pageTitle = $settings['site_title'] ?? 'Swis Brands | Montres de Luxe pour Femmes';
$pageDescription = 'Découvrez Swis Brands, la référence des montres de luxe pour femmes au Maroc. Élégance, style et qualité supérieure. Livraison partout au Maroc.';
$pageKeywords = 'montres femmes, luxe, maroc, swis brands, mode, accessoires, cadeaux, casablanca, rabat, marrakech';
$pageImage = SITE_URL . '/images/logo_swis_rm.png';
$pageUrl = SITE_URL;

// Include header
require_once SWIS_ROOT . '/includes/partials/header.php';
?>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="/images/IMG-20251118-WA0073.jpg" alt="Luxury Watch" class="w-full h-full object-cover">
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

    <!-- SECTION: NEW ARRIVALS -->
    <section id="new-arrivals" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div id="new-arrivals-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Rendered by JS -->
            </div>
        </div>
    </section>

    <!-- SECTION: NEW YEAR COLLECTION -->
    <section id="new-year" class="py-20 bg-brand-black text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-gold opacity-10 rounded-full transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-brand-gold opacity-5 rounded-full transform -translate-x-1/2 translate-y-1/2"></div>
        
        <!-- Floating decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-10 text-brand-gold text-2xl animate-pulse">✦</div>
            <div class="absolute top-20 right-20 text-brand-gold text-xl animate-bounce" style="animation-delay: 0.5s">★</div>
            <div class="absolute bottom-32 left-1/4 text-brand-gold text-lg animate-pulse" style="animation-delay: 1s">✧</div>
            <div class="absolute top-1/3 right-10 text-brand-gold text-2xl animate-bounce" style="animation-delay: 0.3s">✦</div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                <div class="md:w-1/2 text-center md:text-left">
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
                    
                    <!-- Countdown -->
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

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-8" id="packs-container">
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

    <!-- Customer Reviews -->
    <section class="py-20 bg-brand-black text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-brand-gold font-bold tracking-widest uppercase text-sm">Témoignages</span>
                <h2 class="text-4xl font-serif font-bold mb-2 text-white">Nos Clientes Disent</h2>
                <p class="font-arabic text-xl text-gray-400">آراء زبوناتنا الوفيات</p>
                <div class="h-1 w-20 bg-brand-gold mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/5 backdrop-blur-sm p-8 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300">
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

                <div class="bg-white/5 backdrop-blur-sm p-8 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300">
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

                <div class="bg-white/5 backdrop-blur-sm p-8 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300">
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

    <!-- PRODUCT DETAILS MODAL -->
    <div id="product-modal" class="modal fixed inset-0 z-[60] flex items-center justify-center p-3 md:p-4">
        <div class="absolute inset-0 bg-black bg-opacity-80" onclick="closeModal()"></div>
        
        <div class="modal-content bg-white w-full max-h-[88vh] md:max-h-[85vh] md:w-[90%] md:max-w-4xl rounded-2xl shadow-2xl overflow-hidden relative z-10 flex flex-col md:flex-row">
            
            <button onclick="closeModal()" class="absolute top-3 right-3 z-30 bg-white/90 text-gray-600 rounded-full w-9 h-9 flex items-center justify-center shadow-lg hover:text-brand-red transition" aria-label="Fermer">
                <i class="fas fa-times text-lg"></i>
            </button>

            <!-- Image Side -->
            <div class="relative w-full h-[42vh] md:h-auto md:w-1/2 bg-gray-100 flex-shrink-0">
                <img id="modal-image" src="" alt="Watch Detail" class="w-full h-full object-cover transition duration-500">
                
                <button id="modal-prev-btn" onclick="navigateModalImage(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-brand-black w-9 h-9 rounded-full shadow-lg flex items-center justify-center z-10 hidden active:scale-95 transition" aria-label="Image précédente">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="modal-next-btn" onclick="navigateModalImage(1)" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-brand-black w-9 h-9 rounded-full shadow-lg flex items-center justify-center z-10 hidden active:scale-95 transition" aria-label="Image suivante">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <div id="modal-image-counter" class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/70 text-white px-3 py-1 text-xs font-bold rounded-full hidden">
                    <span id="modal-current-index">1</span> / <span id="modal-total-images">1</span>
                </div>
                
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

                    <!-- Variant Selection -->
                    <div id="variant-section" class="mb-3 hidden">
                        <label class="block text-[10px] md:text-xs font-bold uppercase tracking-widest text-brand-black mb-2">
                            Couleur / اللون: <span id="selected-variant-name" class="text-brand-gold ml-1"></span>
                        </label>
                        <div id="variant-options" class="flex flex-wrap gap-2"></div>
                    </div>

                    <!-- Price & Quantity -->
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

                <!-- Actions -->
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

    <!-- Page-Specific JavaScript -->
    <script>
        // Helper function to escape HTML for XSS prevention (defined early for use in rendering)
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Site settings (needed for currency display)
        var siteSettings = {
            whatsappNumber: '<?php echo sanitize($settings['whatsapp_number'] ?? ''); ?>',
            currency: '<?php echo sanitize($settings['currency'] ?? 'DH'); ?>'
        };
        
        // Cart management
        var cart = JSON.parse(localStorage.getItem('swisCart')) || [];
        
        // Product data from PHP
        const newArrivals = <?php echo safeJsonEncode($products['newArrivals'] ?? []); ?>;
        const allProducts = <?php echo safeJsonEncode($products['allProducts'] ?? []); ?>;
        const promoPacks = <?php echo safeJsonEncode($products['promoPacks'] ?? []); ?>;
        
        // State variables
        let visibleProducts = 4;
        let currentSelectedVariant = null;
        let currentModalQty = 1;
        let modalImages = [];
        let currentImageIndex = 0;
        let currentModalProduct = null;
        let currentModalType = 'collection';
        let modalGalleryMode = 'all';
        let allProductImages = [];
        let variantImages = [];

        // Quantity adjustment
        function adjustModalQty(change) {
            currentModalQty += change;
            if (currentModalQty < 1) currentModalQty = 1;
            document.getElementById('modal-qty').textContent = currentModalQty;
        }

        // Countdown to New Year 2026
        function startCountdown() {
            const endDate = new Date('2026-01-01T00:00:00+01:00');
            
            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = endDate - now;
                
                if (distance < 0) {
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

        // Render New Arrivals
        function renderNewArrivals() {
            const grid = document.getElementById('new-arrivals-grid');
            if (!grid || newArrivals.length === 0) {
                if (grid) grid.closest('section').style.display = 'none';
                return;
            }
            
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
                        <p class="text-brand-gold font-bold text-xl">${product.price} ${siteSettings.currency}</p>
                    </div>
                </div>
            `).join('');
        }

        // Render Main Products
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
                        
                        <div class="flex items-center justify-center gap-2 mb-2">
                            ${product.oldPrice ? `<span class="text-gray-400 line-through text-xs md:text-sm">${product.oldPrice} ${siteSettings.currency}</span>` : ''}
                            <p class="text-brand-gold font-bold text-base md:text-xl">${product.price} ${siteSettings.currency}</p>
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

        // Render Packs
        function renderPacks() {
            const container = document.getElementById('packs-container');
            if (!container || promoPacks.length === 0) {
                if (container) container.closest('section').style.display = 'none';
                return;
            }
            
            container.innerHTML = promoPacks.map(pack => `
                <div class="product-card cursor-pointer group relative bg-white pb-2 md:pb-4 rounded-lg overflow-hidden hover:shadow-lg transition duration-300 border border-brand-rose/20" onclick="openModal(${pack.id}, 'pack')">
                    <div class="absolute top-3 left-3 z-10 bg-brand-red text-white text-[10px] md:text-xs font-bold px-2 py-1 rounded-full">PROMO</div>
                    <div class="relative overflow-hidden aspect-w-1 aspect-h-1 h-40 md:h-80 bg-gray-100 mb-2 md:mb-4">
                        <img src="${escapeHtml(pack.image)}" alt="${escapeHtml(pack.name)}" loading="lazy" class="product-img w-full h-full object-cover object-center transition duration-500 ease-in-out">
                        <div class="absolute inset-x-0 bottom-0 p-2 md:p-4 opacity-0 group-hover:opacity-100 transition duration-300 translate-y-4 group-hover:translate-y-0">
                            <span class="block w-full text-center bg-brand-red text-white py-2 md:py-3 uppercase text-[10px] md:text-xs tracking-widest shadow-lg rounded font-bold">
                                Voir Détails
                            </span>
                        </div>
                    </div>
                    <div class="text-center px-2 md:px-4">
                        <p class="text-gray-400 text-[10px] md:text-xs uppercase tracking-wider mb-1 truncate">${escapeHtml(pack.category || 'Pack Promo')}</p>
                        <h3 class="font-serif text-sm md:text-lg text-brand-black mb-0 truncate">${escapeHtml(pack.name)}</h3>
                        <p class="font-arabic text-xs md:text-sm text-gray-500 mb-1 md:mb-2 truncate">${escapeHtml(pack.arabicName)}</p>
                        
                        <div class="flex items-center justify-center gap-2 mb-2">
                            ${pack.oldPrice ? `<span class="text-gray-400 line-through text-xs md:text-sm">${pack.oldPrice} ${siteSettings.currency}</span>` : ''}
                            <p class="text-brand-red font-bold text-base md:text-xl">${pack.price || pack.newPrice} ${siteSettings.currency}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Find product helper
        function findProduct(id, type) {
            if (type === 'pack') return promoPacks.find(p => p.id === id);
            if (type === 'new') return newArrivals.find(p => p.id === id);
            return allProducts.find(p => p.id === id);
        }

        // Open product modal
        function openModal(id, type = 'collection') {
            const product = findProduct(id, type);
            if (!product) return;

            currentModalProduct = product;
            currentModalType = type;

            document.getElementById('modal-title').textContent = product.name;
            document.getElementById('modal-arabic').textContent = product.arabicName;
            
            let currentPrice = product.price || product.newPrice;
            document.getElementById('modal-price').textContent = currentPrice + ' ' + siteSettings.currency;
            document.getElementById('modal-category').textContent = product.category || 'Pack Promo';

            const oldPriceEl = document.getElementById('modal-old-price');
            if (product.oldPrice) {
                oldPriceEl.textContent = product.oldPrice + ' ' + siteSettings.currency;
                oldPriceEl.classList.remove('hidden');
            } else {
                oldPriceEl.classList.add('hidden');
            }

            const variantSection = document.getElementById('variant-section');
            const variantOptionsDiv = document.getElementById('variant-options');
            currentSelectedVariant = null;
            currentModalQty = 1;
            document.getElementById('modal-qty').textContent = currentModalQty;

            const hasVariants = product.variants && product.variants.length > 0;
            const hasGallery = product.gallery && product.gallery.length > 0;

            modalImages = [product.image];
            if (hasGallery) {
                product.gallery.forEach(img => {
                    if (!modalImages.includes(img)) modalImages.push(img);
                });
            }
            
            allProductImages = [...modalImages];
            variantImages = [...modalImages];
            modalGalleryMode = 'all';
            currentImageIndex = 0;
            
            document.getElementById('modal-image').src = product.image;
            document.getElementById('modal-gallery-toggle').classList.add('hidden');
            updateImageNavigation();

            if (hasVariants) {
                variantSection.classList.remove('hidden');
                currentSelectedVariant = product.variants[0];
                document.getElementById('selected-variant-name').textContent = currentSelectedVariant.name;

                let thumbnailsHtml = product.variants.map((v, index) => `
                    <div onclick="selectVariant(${id}, ${index}, '${type}')" 
                         class="variant-option cursor-pointer rounded-lg overflow-hidden w-14 h-14 md:w-16 md:h-16 relative shadow-sm hover:shadow-md border-2 ${index === 0 ? 'selected border-brand-gold' : 'border-transparent'}"
                         title="${escapeHtml(v.name)}">
                        <img src="${escapeHtml(v.image || product.image)}" class="w-full h-full object-cover pointer-events-none" alt="${escapeHtml(v.name)}">
                        <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[8px] py-0.5 text-center truncate px-0.5">${escapeHtml(v.name)}</div>
                    </div>
                `).join('');
                
                variantOptionsDiv.innerHTML = thumbnailsHtml;
                
                const firstVariant = product.variants[0];
                document.getElementById('modal-image').src = firstVariant.image || product.image;
                
                variantImages = [];
                if (firstVariant.image) variantImages.push(firstVariant.image);
                if (firstVariant.gallery && firstVariant.gallery.length > 0) {
                    firstVariant.gallery.forEach(img => {
                        if (!variantImages.includes(img)) variantImages.push(img);
                    });
                }
                if (variantImages.length === 0) variantImages.push(product.image);
                
                modalGalleryMode = 'variant';
                modalImages = [...variantImages];
                currentImageIndex = 0;
                
                document.getElementById('modal-gallery-toggle').classList.remove('hidden');
                document.getElementById('gallery-toggle-text').textContent = 'Toutes';
                
                updateImageNavigation();
            } else {
                variantSection.classList.add('hidden');
            }

            document.getElementById('modal-add-btn').onclick = function() {
                addToCart(product.id, type, currentModalQty);
                closeModal();
            };

            document.getElementById('modal-buy-now-btn').onclick = function() {
                addToCart(product.id, type, currentModalQty);
                closeModal();
                openCheckoutModal();
            };

            const modal = document.getElementById('product-modal');
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.add('active'); }, 10);
            
            updateUrlForProduct(id, type);
        }

        // Select variant
        function selectVariant(productId, variantIndex, type) {
            const product = currentModalProduct;
            if (!product || !product.variants || !product.variants[variantIndex]) return;
            
            const variant = product.variants[variantIndex];
            currentSelectedVariant = variant;
            
            variantImages = [];
            if (variant.image) variantImages.push(variant.image);
            if (variant.gallery && variant.gallery.length > 0) {
                variant.gallery.forEach(img => {
                    if (!variantImages.includes(img)) variantImages.push(img);
                });
            }
            if (variantImages.length === 0) variantImages.push(product.image);
            
            modalGalleryMode = 'variant';
            modalImages = [...variantImages];
            currentImageIndex = 0;
            
            document.getElementById('modal-image').src = modalImages[0];
            document.getElementById('selected-variant-name').textContent = variant.name;
            document.getElementById('gallery-toggle-text').textContent = 'Toutes';
            
            updateImageNavigation();
            
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
                modalGalleryMode = 'all';
                modalImages = [...allProductImages];
                document.getElementById('gallery-toggle-text').textContent = currentSelectedVariant ? currentSelectedVariant.name : 'Variante';
            } else {
                modalGalleryMode = 'variant';
                modalImages = [...variantImages];
                document.getElementById('gallery-toggle-text').textContent = 'Toutes';
            }
            
            currentImageIndex = 0;
            document.getElementById('modal-image').src = modalImages[0];
            updateImageNavigation();
        }

        function navigateModalImage(direction) {
            if (modalImages.length <= 1) return;
            
            currentImageIndex += direction;
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
            clearProductUrl();
        }

        // Add to cart
        function addToCart(id, type = 'collection', qty = 1) {
            const product = findProduct(id, type);
            if (!product) return;
            
            const variantName = currentSelectedVariant ? currentSelectedVariant.name : "Standard";
            const variantImage = currentSelectedVariant ? currentSelectedVariant.image : product.image;
            const finalPrice = product.price || product.newPrice;

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

        // URL management
        function updateUrlForProduct(id, type) {
            const url = new URL(window.location);
            url.searchParams.set('product', id);
            url.searchParams.set('type', type);
            window.history.replaceState({}, '', url);
        }

        function clearProductUrl() {
            const url = new URL(window.location);
            url.searchParams.delete('product');
            url.searchParams.delete('type');
            window.history.replaceState({}, '', url);
        }

        function checkDeepLink() {
            const urlParams = new URLSearchParams(window.location.search);
            const productId = urlParams.get('product');
            const productType = urlParams.get('type') || 'auto';
            
            if (productId) {
                const id = parseInt(productId);
                let foundType = null;
                let product = null;
                
                if (productType === 'pack') {
                    product = promoPacks.find(p => p.id === id);
                    if (product) foundType = 'pack';
                } else if (productType === 'new') {
                    product = newArrivals.find(p => p.id === id);
                    if (product) foundType = 'new';
                } else if (productType === 'collection') {
                    product = allProducts.find(p => p.id === id);
                    if (product) foundType = 'collection';
                }
                
                if (!product) {
                    product = allProducts.find(p => p.id === id);
                    if (product) foundType = 'collection';
                }
                if (!product) {
                    product = newArrivals.find(p => p.id === id);
                    if (product) foundType = 'new';
                }
                if (!product) {
                    product = promoPacks.find(p => p.id === id);
                    if (product) foundType = 'pack';
                }
                
                if (product && foundType) {
                    setTimeout(() => { openModal(id, foundType); }, 300);
                }
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(event) {
            const productModal = document.getElementById('product-modal');
            if (productModal && productModal.classList.contains('active')) {
                if (event.key === 'ArrowLeft') navigateModalImage(-1);
                else if (event.key === 'ArrowRight') navigateModalImage(1);
                else if (event.key === 'Escape') closeModal();
            }
        });

        // Load more button
        document.getElementById('load-more-btn').addEventListener('click', () => {
            visibleProducts += 4;
            renderProducts();
        });

        // Initialize
        startCountdown();
        renderProducts();
        renderNewArrivals();
        renderPacks();
        checkDeepLink();
    </script>

<?php
// Include footer
require_once SWIS_ROOT . '/includes/partials/footer.php';
?>
