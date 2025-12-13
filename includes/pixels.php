<?php
/**
 * ═══════════════════════════════════════════════════════════════════════════
 *                        PIXEL TRACKING MODULE
 *                           Swis Brands - v2.0.0
 * ═══════════════════════════════════════════════════════════════════════════
 * 
 * This file reads pixel IDs from the admin panel (data/tracking.json)
 * 
 * To configure your pixels:
 *   1. Go to Admin Panel → Tracking & Pixels
 *   2. Paste your Facebook Pixel ID and/or TikTok Pixel ID
 *   3. Save changes
 * 
 * Events tracked:
 *   ✓ PageView  - Fires on ALL pages (automatic)
 *   ✓ Purchase  - Fires when order is submitted via WhatsApp
 * 
 * ═══════════════════════════════════════════════════════════════════════════
 */

// Prevent direct access
if (!defined('SWIS_ROOT')) {
    die('Direct access not allowed');
}

/**
 * Get pixel configuration from tracking.json
 * 
 * @return array Tracking configuration
 */
function getPixelConfig(): array {
    static $config = null;
    
    if ($config === null) {
        $trackingFile = SWIS_ROOT . '/data/tracking.json';
        
        if (file_exists($trackingFile)) {
            $content = file_get_contents($trackingFile);
            $config = json_decode($content, true);
            
            if ($config === null) {
                $config = [];
            }
        } else {
            $config = [];
        }
    }
    
    return $config;
}

/**
 * Render tracking pixels for the <head> section
 * Call this function inside <head> tags on every page
 * 
 * @return string HTML/JS tracking code
 */
function renderPixels(): string {
    $config = getPixelConfig();
    $output = '';
    
    // Facebook/Meta Pixel
    $fbPixelId = trim($config['facebook_pixel_id'] ?? '');
    if (!empty($fbPixelId)) {
        $fbId = htmlspecialchars($fbPixelId, ENT_QUOTES, 'UTF-8');
        $output .= <<<HTML

    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{$fbId}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={$fbId}&ev=PageView&noscript=1"/></noscript>
    <!-- End Meta Pixel Code -->
HTML;
    }
    
    // TikTok Pixel
    $ttPixelId = trim($config['tiktok_pixel_id'] ?? '');
    if (!empty($ttPixelId)) {
        $ttId = htmlspecialchars($ttPixelId, ENT_QUOTES, 'UTF-8');
        $output .= <<<HTML

    <!-- TikTok Pixel Code -->
    <script>
    !function (w, d, t) {
      w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
      ttq.load('{$ttId}');
      ttq.page();
    }(window, document, 'ttq');
    </script>
    <!-- End TikTok Pixel Code -->
HTML;
    }
    
    // Google Analytics 4
    $gaId = trim($config['google_analytics_id'] ?? '');
    if (!empty($gaId)) {
        $gaId = htmlspecialchars($gaId, ENT_QUOTES, 'UTF-8');
        $output .= <<<HTML

    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={$gaId}"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{$gaId}');
    </script>
    <!-- End Google Analytics -->
HTML;
    }
    
    // Google Ads
    $gadsId = trim($config['google_ads_id'] ?? '');
    if (!empty($gadsId)) {
        $gadsId = htmlspecialchars($gadsId, ENT_QUOTES, 'UTF-8');
        $output .= <<<HTML

    <!-- Google Ads -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={$gadsId}"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{$gadsId}');
    </script>
    <!-- End Google Ads -->
HTML;
    }
    
    // Snapchat Pixel
    $snapId = trim($config['snapchat_pixel_id'] ?? '');
    if (!empty($snapId)) {
        $snapId = htmlspecialchars($snapId, ENT_QUOTES, 'UTF-8');
        $output .= <<<HTML

    <!-- Snapchat Pixel -->
    <script type='text/javascript'>
    (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
    {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
    a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
    r.src=n;var u=t.getElementsByTagName(s)[0];
    u.parentNode.insertBefore(r,u);})(window,document,
    'https://sc-static.net/scevent.min.js');
    snaptr('init', '{$snapId}');
    snaptr('track', 'PAGE_VIEW');
    </script>
    <!-- End Snapchat Pixel -->
HTML;
    }
    
    // Custom Head Scripts
    $customHead = trim($config['custom_head_scripts'] ?? '');
    if (!empty($customHead)) {
        $output .= "\n    <!-- Custom Head Scripts -->\n    " . $customHead . "\n";
    }
    
    return $output;
}


/**
 * Render Purchase event tracking
 * Call this function after successful order submission
 * 
 * @param float  $value    Order total value
 * @param string $currency Currency code (default: MAD)
 * @param string $orderId  Optional order ID for deduplication
 * @return string HTML/JS tracking code
 */
function renderPurchaseEvent(float $value, string $currency = 'MAD', string $orderId = ''): string {
    $config = getPixelConfig();
    $output = '';
    $safeValue = number_format($value, 2, '.', '');
    $safeCurrency = htmlspecialchars($currency, ENT_QUOTES, 'UTF-8');
    $safeOrderId = htmlspecialchars($orderId, ENT_QUOTES, 'UTF-8');
    
    // Facebook Purchase Event
    $fbPixelId = trim($config['facebook_pixel_id'] ?? '');
    if (!empty($fbPixelId)) {
        $eventId = $safeOrderId ?: uniqid('fb_', true);
        $output .= <<<HTML

    <!-- Meta Pixel Purchase Event -->
    <script>
    fbq('track', 'Purchase', {
        value: {$safeValue},
        currency: '{$safeCurrency}'
    }, {eventID: '{$eventId}'});
    </script>
HTML;
    }
    
    // TikTok Purchase Event
    $ttPixelId = trim($config['tiktok_pixel_id'] ?? '');
    if (!empty($ttPixelId)) {
        $output .= <<<HTML

    <!-- TikTok Purchase Event -->
    <script>
    ttq.track('CompletePayment', {
        value: {$safeValue},
        currency: '{$safeCurrency}'
    });
    </script>
HTML;
    }
    
    // Google Analytics Purchase Event
    $gaId = trim($config['google_analytics_id'] ?? '');
    if (!empty($gaId)) {
        $output .= <<<HTML

    <!-- Google Analytics Purchase Event -->
    <script>
    gtag('event', 'purchase', {
        value: {$safeValue},
        currency: '{$safeCurrency}',
        transaction_id: '{$safeOrderId}'
    });
    </script>
HTML;
    }
    
    // Google Ads Conversion
    $gadsId = trim($config['google_ads_id'] ?? '');
    if (!empty($gadsId)) {
        $output .= <<<HTML

    <!-- Google Ads Conversion -->
    <script>
    gtag('event', 'conversion', {
        'send_to': '{$gadsId}',
        'value': {$safeValue},
        'currency': '{$safeCurrency}',
        'transaction_id': '{$safeOrderId}'
    });
    </script>
HTML;
    }
    
    // Snapchat Purchase Event
    $snapId = trim($config['snapchat_pixel_id'] ?? '');
    if (!empty($snapId)) {
        $output .= <<<HTML

    <!-- Snapchat Purchase Event -->
    <script>
    snaptr('track', 'PURCHASE', {
        'price': {$safeValue},
        'currency': '{$safeCurrency}',
        'transaction_id': '{$safeOrderId}'
    });
    </script>
HTML;
    }
    
    return $output;
}

/**
 * Render custom body scripts (for end of body)
 * 
 * @return string HTML/JS tracking code
 */
function renderBodyScripts(): string {
    $config = getPixelConfig();
    $output = '';
    
    $customBody = trim($config['custom_body_scripts'] ?? '');
    if (!empty($customBody)) {
        $output .= "\n    <!-- Custom Body Scripts -->\n    " . $customBody . "\n";
    }
    
    return $output;
}

/**
 * Generate Facebook Pixel ViewContent event for product pages
 * 
 * @param int    $productId   Product ID
 * @param string $productName Product name
 * @param float  $price       Product price
 * @param string $currency    Currency code (default: MAD)
 * @return string JavaScript code
 */
function fbPixelViewContent(int $productId, string $productName, float $price, string $currency = 'MAD'): string {
    $config = getPixelConfig();
    $output = '';
    
    // Facebook ViewContent
    $fbPixelId = trim($config['facebook_pixel_id'] ?? '');
    if (!empty($fbPixelId)) {
        $name = htmlspecialchars($productName, ENT_QUOTES, 'UTF-8');
        $output .= "fbq('track', 'ViewContent', {content_ids: ['$productId'], content_name: '$name', content_type: 'product', value: $price, currency: '$currency'});";
    }
    
    // TikTok ViewContent
    $ttPixelId = trim($config['tiktok_pixel_id'] ?? '');
    if (!empty($ttPixelId)) {
        $name = htmlspecialchars($productName, ENT_QUOTES, 'UTF-8');
        if (!empty($output)) $output .= "\n        ";
        $output .= "ttq.track('ViewContent', {content_id: '$productId', content_name: '$name', content_type: 'product', value: $price, currency: '$currency'});";
    }
    
    return $output;
}

/**
 * Generate TikTok Pixel event code
 * 
 * @param string $eventName Event name
 * @param array  $params    Event parameters
 * @return string JavaScript code
 */
function ttPixelEvent(string $eventName, array $params = []): string {
    $config = getPixelConfig();
    if (empty($config['tiktok_pixel_id'])) {
        return '';
    }
    
    $paramsJson = json_encode($params, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    return "ttq.track('$eventName', $paramsJson);";
}
