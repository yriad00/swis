<?php
/**
 * Swis Brands - Footer Partial
 * Includes footer, cart sidebar, modals, and closing scripts
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('SWIS_ROOT')) {
    die('Direct access not allowed');
}

$settings = getSettings();
?>

    <!-- Shopping Cart Sidebar -->
    <div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full md:w-96 bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="h-full flex flex-col">
            <div class="p-6 border-b flex justify-between items-center bg-brand-black text-white">
                <div>
                    <h2 class="font-serif text-xl">Mon Panier</h2>
                    <p class="font-arabic text-xs text-gray-400">سلة المشتريات</p>
                </div>
                <button onclick="toggleCart()" class="text-gray-400 hover:text-white" aria-label="Fermer">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4"></div>
            <div class="p-6 border-t bg-gray-50">
                <div class="flex justify-between mb-4 font-bold text-lg">
                    <span>Total / المجموع</span>
                    <span id="cart-total">0.00 <?php echo sanitize($settings['currency'] ?? 'DH'); ?></span>
                </div>
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

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="modal fixed inset-0 z-[70] flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-70" onclick="closeCheckoutModal()"></div>
        <div class="modal-content bg-white w-full max-w-md rounded-2xl shadow-2xl relative z-10 p-8">
            <button onclick="closeCheckoutModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500" aria-label="Fermer">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <div class="text-center mb-6">
                <h2 class="font-serif text-2xl font-bold text-brand-black mb-2">Finaliser la Commande</h2>
                <p class="text-gray-500 text-sm font-arabic">أكملي المعلومات للطلب</p>
            </div>

            <form id="checkout-form" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Nom Complet / الاسم الكامل</label>
                    <input type="text" id="checkout-name" required class="form-input w-full border border-gray-300 rounded-lg p-3 text-sm" placeholder="Votre nom...">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Téléphone / الهاتف</label>
                    <input type="tel" id="checkout-phone" required class="form-input w-full border border-gray-300 rounded-lg p-3 text-sm" placeholder="06 XX XX XX XX">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Ville / المدينة</label>
                    <select id="checkout-city" required class="form-select w-full border border-gray-300 rounded-lg p-3 text-sm bg-white">
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
                    <input type="text" id="checkout-address" required class="form-input w-full border border-gray-300 rounded-lg p-3 text-sm" placeholder="Ex: Quartier, Rue, N°...">
                </div>
                
                <button type="submit" class="w-full bg-green-600 text-white py-4 mt-4 rounded-lg uppercase tracking-widest font-bold hover:bg-green-700 transition shadow-lg flex items-center justify-center gap-2">
                    <span>Confirmer sur WhatsApp</span>
                    <i class="fab fa-whatsapp text-lg"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-brand-black text-white px-6 py-3 rounded shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none z-[80] flex items-center gap-2 w-max">
        <i class="fas fa-check-circle text-green-500"></i>
        <span>Ajouté au panier!</span>
    </div>

    <!-- Footer -->
    <footer class="bg-brand-black text-white py-16 border-t-4 border-brand-gold">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
            <div class="flex flex-col items-center md:items-start">
                <img src="/images/Gemini_Generated_Image_82jog382jog382jo.png" alt="Swis Brands Logo" class="h-20 w-auto object-contain mb-6 bg-white p-2 rounded">
                <p class="text-gray-400 text-sm leading-relaxed mb-4">
                    Swis Brands est plus qu'une montre. C'est une déclaration d'élégance pour la femme marocaine moderne.
                </p>
            </div>
            <div>
                <h4 class="font-serif text-lg mb-6 text-brand-gold uppercase tracking-widest">Contactez-nous</h4>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo sanitize($settings['whatsapp_number'] ?? ''); ?>" class="flex items-center gap-2 hover:text-brand-gold transition">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-serif text-lg mb-6 text-brand-gold uppercase tracking-widest">Suivez-nous</h4>
                <div class="flex space-x-4 justify-center md:justify-start">
                    <a href="https://www.instagram.com/swis_maroc/" target="_blank" rel="noopener" class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-white transition transform hover:-translate-y-1">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-600 text-xs">
            &copy; <?php echo date('Y'); ?> Swis Brands. All rights reserved.
        </div>
    </footer>

    <!-- Core JavaScript Functions -->
    <script>
        // Site settings (only define if not already defined)
        if (typeof siteSettings === 'undefined') {
            var siteSettings = {
                whatsappNumber: '<?php echo sanitize($settings['whatsapp_number'] ?? ''); ?>',
                currency: '<?php echo sanitize($settings['currency'] ?? 'DH'); ?>'
            };
        }

        // Cart management
        if (typeof cart === 'undefined') {
            var cart = JSON.parse(localStorage.getItem('swisCart')) || [];
        }

        // Helper function to escape HTML for XSS prevention (only define if not already defined)
        if (typeof escapeHtml === 'undefined') {
            function escapeHtml(text) {
                if (text === null || text === undefined) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        }

        // Toggle cart sidebar
        function toggleCart() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            sidebar.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Toggle mobile menu
        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }

        // Update cart UI
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
                            <p class="text-xs text-brand-gold font-bold">${item.price} ${siteSettings.currency}</p>
                            <div class="flex items-center mt-2 space-x-2">
                                <button onclick="updateQuantity('${escapeHtml(item.cartId)}', -1)" class="w-6 h-6 rounded-full bg-gray-100 text-xs">-</button>
                                <span class="text-xs font-semibold w-4 text-center">${item.quantity}</span>
                                <button onclick="updateQuantity('${escapeHtml(item.cartId)}', 1)" class="w-6 h-6 rounded-full bg-gray-100 text-xs">+</button>
                            </div>
                        </div>
                        <div class="text-right flex flex-col justify-between h-16">
                            <p class="font-bold text-sm">${item.price * item.quantity} ${siteSettings.currency}</p>
                            <button onclick="removeFromCart('${escapeHtml(item.cartId)}')" class="text-xs text-red-400 hover:text-red-600 transition underline"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                `).join('');
            }
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('cart-total').textContent = total + " " + siteSettings.currency;
        }

        // Save cart to localStorage
        function saveCart() {
            localStorage.setItem('swisCart', JSON.stringify(cart));
        }

        // Remove item from cart
        function removeFromCart(cartId) {
            cart = cart.filter(item => item.cartId !== cartId);
            saveCart();
            updateCartUI();
        }

        // Update item quantity
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

        // Show toast notification
        function showToast(msg) {
            const toast = document.getElementById('toast');
            toast.querySelector('span').textContent = msg;
            toast.style.opacity = '1';
            setTimeout(() => { toast.style.opacity = '0'; }, 3000);
        }

        // Checkout modal functions
        function openCheckoutModal() {
            if (cart.length === 0) return showToast("Votre panier est vide!");
            const modal = document.getElementById('checkout-modal');
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.add('active'); }, 10);
            toggleCart();
        }

        function closeCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            modal.classList.remove('active');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        // Handle checkout form submission
        document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('checkout-name').value;
            const phone = document.getElementById('checkout-phone').value;
            const city = document.getElementById('checkout-city').value;
            const address = document.getElementById('checkout-address').value;

            if (!name || !phone || !city || !address) {
                alert("Merci de remplir toutes les informations / المرجو ملء جميع المعلومات");
                return;
            }

            let msg = `👋 *NOUVELLE COMMANDE / طلب جديد* \n`;
            msg += `--------------------------------\n`;
            msg += `👤 *Client:* ${name}\n`;
            msg += `📱 *Tel:* ${phone}\n`;
            msg += `🏙️ *Ville:* ${city}\n`;
            msg += `📍 *Adresse:* ${address}\n`;
            msg += `--------------------------------\n\n`;
            msg += `*ARTICLES:*\n`;

            let total = 0;
            const contentIds = [];
            const contents = [];
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                msg += `▪️ ${item.name} (${item.variant}) x${item.quantity} - ${itemTotal} MAD\n`;
                total += itemTotal;
                contentIds.push(String(item.id));
                contents.push({
                    content_id: String(item.id),
                    content_name: item.name,
                    quantity: item.quantity,
                    price: item.price
                });
            });
            msg += `\n💰 *TOTAL: ${total} MAD*`;
            
            // Fire Purchase tracking events with all required data
            trackPurchase(total, 'MAD', phone, name, contentIds, contents);
            
            closeCheckoutModal();
            
            // Delay WhatsApp redirect to allow pixels to send (500ms)
            const whatsappUrl = `https://api.whatsapp.com/send?phone=${siteSettings.whatsappNumber}&text=${encodeURIComponent(msg)}`;
            setTimeout(() => {
                window.open(whatsappUrl, '_blank');
            }, 500);
            
            // Clear cart after purchase
            cart = [];
            updateCartUI();
            saveCart();
        });

        // Track Purchase Event (Facebook + TikTok + Google Analytics + Snapchat)
        function trackPurchase(value, currency, phone, customerName, contentIds, contents) {
            // Ensure currency is always MAD (TikTok requires ISO currency code)
            const isoCurrency = 'MAD';
            const orderId = 'ORDER_' + Date.now();
            
            // Format phone for Morocco (remove spaces, ensure +212 format)
            let formattedPhone = '';
            if (phone) {
                formattedPhone = phone.replace(/\s+/g, '').replace(/^0/, '+212');
                if (!formattedPhone.startsWith('+')) {
                    formattedPhone = '+212' + formattedPhone.replace(/^212/, '');
                }
            }
            
            // Facebook Pixel Purchase Event with Advanced Matching
            if (typeof fbq !== 'undefined') {
                // Set user data for Advanced Matching
                if (formattedPhone || customerName) {
                    fbq('init', '<?php echo sanitize(getPixelConfig()['facebook_pixel_id'] ?? ''); ?>', {
                        ph: formattedPhone,
                        fn: customerName ? customerName.split(' ')[0].toLowerCase() : ''
                    });
                }
                fbq('track', 'Purchase', {
                    value: value,
                    currency: isoCurrency,
                    content_ids: contentIds,
                    content_type: 'product',
                    num_items: contents.length
                }, {eventID: orderId});
                console.log('FB Pixel: Purchase event fired', {value, currency: isoCurrency, contentIds});
            }
            
            // TikTok Pixel Purchase Event with Advanced Matching and content_id
            if (typeof ttq !== 'undefined') {
                // Identify user for Advanced Matching
                if (formattedPhone) {
                    ttq.identify({
                        phone_number: formattedPhone
                    });
                }
                
                ttq.track('CompletePayment', {
                    value: value,
                    currency: isoCurrency,
                    content_id: contentIds.join(','),
                    content_type: 'product',
                    content_name: contents.map(c => c.content_name).join(', '),
                    quantity: contents.reduce((sum, c) => sum + c.quantity, 0),
                    contents: contents
                });
                console.log('TikTok Pixel: CompletePayment event fired', {value, currency: isoCurrency, contentIds});
            }
            
            // Google Analytics Purchase Event
            if (typeof gtag !== 'undefined') {
                gtag('event', 'purchase', {
                    value: value,
                    currency: isoCurrency,
                    transaction_id: orderId,
                    items: contents.map(c => ({
                        item_id: c.content_id,
                        item_name: c.content_name,
                        quantity: c.quantity,
                        price: c.price
                    }))
                });
                console.log('GA4: Purchase event fired', {value, currency: isoCurrency, orderId});
            }
            
            // Snapchat Purchase Event
            if (typeof snaptr !== 'undefined') {
                snaptr('track', 'PURCHASE', {
                    price: value,
                    currency: isoCurrency,
                    transaction_id: orderId,
                    item_ids: contentIds
                });
                console.log('Snapchat: PURCHASE event fired', {value, currency: isoCurrency});
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navbar');
            const mobileMenu = document.getElementById('mobile-menu');
            if (nav && mobileMenu && !nav.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Navbar shadow on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (nav) {
                window.scrollY > 50 ? nav.classList.add('shadow-md') : nav.classList.remove('shadow-md');
            }
        });

        // Initialize cart UI
        updateCartUI();
    </script>
    
    <!-- Custom Body Scripts -->
    <?php echo renderBodyScripts(); ?>
</body>
</html>
